<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaAbertura extends Model
{
    use HasFactory;

    protected $table = 'caixa_aberturas';

    protected $fillable = [
        'evento_id',
        'user_id',
        'data_abertura',
        'hora_abertura',
        'datahora_abertura',
        'valor_inicial',
        'valor_final',
        'datahora_fechamento',
        'user_fechamento_id',
        'status',
        'observacoes_abertura',
        'observacoes_fechamento',
        'ativo',
    ];

    protected $casts = [
        'data_abertura' => 'date',
        'datahora_abertura' => 'datetime',
        'datahora_fechamento' => 'datetime',
        'valor_inicial' => 'decimal:2',
        'valor_final' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userFechamento()
    {
        return $this->belongsTo(User::class, 'user_fechamento_id');
    }

    public function caixaMovimentos()
    {
        return $this->hasMany(CaixaMovimento::class);
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

    public function scopeAbertos($query)
    {
        return $query->where('status', 'aberto');
    }

    public function scopeFechados($query)
    {
        return $query->where('status', 'fechado');
    }

    public function scopePorData($query, $data)
    {
        return $query->whereDate('data_abertura', $data);
    }

    // Accessors
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'aberto' => 'Aberto',
                'fechado' => 'Fechado',
                default => ucfirst($this->status)
            }
        );
    }

    protected function valorInicialFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ '.number_format($this->valor_inicial, 2, ',', '.'),
        );
    }

    protected function valorFinalFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->valor_final ? 'R$ '.number_format($this->valor_final, 2, ',', '.') : '-',
        );
    }

    protected function diferenca(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->valor_final ? $this->valor_final - $this->valor_inicial : null,
        );
    }

    protected function tempoAberto(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'fechado' && $this->datahora_fechamento) {
                    return $this->datahora_abertura->diffForHumans($this->datahora_fechamento, true);
                }

                return $this->datahora_abertura->diffForHumans(now(), true);
            }
        );
    }

    // Métodos auxiliares
    public function fechar($valorFinal, $userId, $observacoes = null): bool
    {
        if (! $this->podeFechar()) {
            return false;
        }

        $this->valor_final = $valorFinal;
        $this->datahora_fechamento = now();
        $this->user_fechamento_id = $userId;
        $this->status = 'fechado';
        $this->observacoes_fechamento = $observacoes;

        return $this->save();
    }

    public function podeFechar(): bool
    {
        return $this->status === 'aberto' && $this->ativo;
    }

    public function calcularSaldoEsperado(): float
    {
        $entradas = $this->caixaMovimentos()
            ->where('ativo', true)
            ->where('tipo_item', 'entrada')
            ->sum('valor');

        $saidas = $this->caixaMovimentos()
            ->where('ativo', true)
            ->where('tipo_item', 'saida')
            ->sum('valor');

        $ajustes = $this->caixaMovimentos()
            ->where('ativo', true)
            ->where('tipo_item', 'ajuste')
            ->sum('valor');

        return (float) ($this->valor_inicial + $entradas - $saidas + $ajustes);
    }

    public function calcularDiferenca(): float
    {
        if (! $this->valor_final) {
            return 0;
        }

        $saldoEsperado = $this->calcularSaldoEsperado();

        return (float) ($this->valor_final - $saldoEsperado);
    }
}
