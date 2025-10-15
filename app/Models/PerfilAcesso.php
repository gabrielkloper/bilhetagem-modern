<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PerfilAcesso extends Model
{
    use HasFactory;

    protected $table = 'perfis_acesso';

    protected $fillable = [
        'evento_id',
        'titulo',
        'descricao', 
        'ativo',
        'padrao_evento',
        'preco_base',
        'idade_minima',
        'idade_maxima',
        'altura_minima',
        'altura_maxima',
        'tipo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'padrao_evento' => 'boolean',
        'preco_base' => 'decimal:2',
        'altura_minima' => 'decimal:2',
        'altura_maxima' => 'decimal:2',
        'idade_minima' => 'integer',
        'idade_maxima' => 'integer'
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'perfil_acesso_id');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    public function scopePadrao($query)
    {
        return $query->where('padrao_evento', true);
    }

    // Métodos auxiliares
    public function podeUsarPerfil($idade = null, $altura = null): bool
    {
        if ($this->tipo === 'padrao') {
            return true;
        }

        if ($this->tipo === 'idade' && $idade !== null) {
            $minimaOk = $this->idade_minima === null || $idade >= $this->idade_minima;
            $maximaOk = $this->idade_maxima === null || $idade <= $this->idade_maxima;
            return $minimaOk && $maximaOk;
        }

        if ($this->tipo === 'altura' && $altura !== null) {
            $minimaOk = $this->altura_minima === null || $altura >= $this->altura_minima;
            $maximaOk = $this->altura_maxima === null || $altura <= $this->altura_maxima;
            return $minimaOk && $maximaOk;
        }

        if ($this->tipo === 'especial') {
            return true; // Perfis especiais (PCD, etc.) são selecionados manualmente
        }

        return false;
    }

    public static function obterPerfilParaVinculado(Vinculado $vinculado, $eventoId)
    {
        $idade = $vinculado->idade;
        $perfis = static::ativos()->porEvento($eventoId)->get();

        // Primeiro, procura perfis específicos que se aplicam
        foreach ($perfis as $perfil) {
            if ($perfil->podeUsarPerfil($idade)) {
                return $perfil;
            }
        }

        // Se nenhum perfil específico, retorna o padrão
        return static::ativos()->porEvento($eventoId)->padrao()->first();
    }

    // Accessors
    protected function precoFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ ' . number_format($this->preco_base, 2, ',', '.')
        );
    }

    protected function criteriosTexto(): Attribute
    {
        return Attribute::make(
            get: function () {
                $criterios = [];
                
                if ($this->idade_minima || $this->idade_maxima) {
                    $texto = 'Idade: ';
                    if ($this->idade_minima && $this->idade_maxima) {
                        $texto .= "{$this->idade_minima} a {$this->idade_maxima} anos";
                    } elseif ($this->idade_minima) {
                        $texto .= "a partir de {$this->idade_minima} anos";
                    } elseif ($this->idade_maxima) {
                        $texto .= "até {$this->idade_maxima} anos";
                    }
                    $criterios[] = $texto;
                }
                
                if ($this->altura_minima || $this->altura_maxima) {
                    $texto = 'Altura: ';
                    if ($this->altura_minima && $this->altura_maxima) {
                        $texto .= "{$this->altura_minima}m a {$this->altura_maxima}m";
                    } elseif ($this->altura_minima) {
                        $texto .= "a partir de {$this->altura_minima}m";
                    } elseif ($this->altura_maxima) {
                        $texto .= "até {$this->altura_maxima}m";
                    }
                    $criterios[] = $texto;
                }
                
                return implode(' | ', $criterios);
            }
        );
    }
}
