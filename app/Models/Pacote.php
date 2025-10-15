<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pacote extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'rotulo_cliente',
        'evento_id',
        'ativo',
        'valor',
        'duracao',
        'tolerancia',
        'valor_minuto_adicional'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'valor' => 'decimal:2',
        'valor_minuto_adicional' => 'decimal:2',
        'duracao' => 'integer',
        'tolerancia' => 'integer'
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class);
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

    // Accessors
    protected function duracaoFormatada(): Attribute
    {
        return Attribute::make(
            get: function () {
                $horas = intdiv($this->duracao, 60);
                $minutos = $this->duracao % 60;
                return $horas > 0 ? "{$horas}h {$minutos}min" : "{$minutos}min";
            },
        );
    }

    protected function valorFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ ' . number_format($this->valor, 2, ',', '.'),
        );
    }

    // Métodos auxiliares
    public function calcularValorTotal($minutosUtilizados)
    {
        $minutosInclusos = $this->duracao + $this->tolerancia;
        
        if ($minutosUtilizados <= $minutosInclusos) {
            return $this->valor;
        }

        $minutosExcedentes = $minutosUtilizados - $minutosInclusos;
        return $this->valor + ($minutosExcedentes * $this->valor_minuto_adicional);
    }
}
