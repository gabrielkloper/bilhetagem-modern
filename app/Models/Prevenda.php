<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prevenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'responsavel_id',
        'evento_id',
        'data_acesso',
        'status',
        'datahora_solicita',
        'datahora_efetiva',
        'datahora_efetiva_saida',
        'tipo_pagamento',
        'valor_pagamento',
        'datahora_pagamento',
        'datahora_reserva',
        'origem',
    ];

    protected $casts = [
        'data_acesso' => 'date',
        'datahora_solicita' => 'datetime',
        'datahora_efetiva' => 'datetime',
        'datahora_efetiva_saida' => 'datetime',
        'datahora_pagamento' => 'datetime',
        'datahora_reserva' => 'datetime',
        'valor_pagamento' => 'decimal:2',
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }

    // Scopes
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    public function scopePorResponsavel($query, $responsavelId)
    {
        return $query->where('responsavel_id', $responsavelId);
    }

    // Accessors
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'pendente' => 'Pendente',
                'confirmado' => 'Confirmado',
                'cancelado' => 'Cancelado',
                'utilizado' => 'Utilizado',
                'expirado' => 'Expirado',
                'finalizado' => 'Finalizado',
                default => ucfirst($this->status)
            }
        );
    }

    protected function valorFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ '.number_format($this->valor_pagamento, 2, ',', '.')
        );
    }

    // Métodos auxiliares
    public function podeSerUtilizada(): bool
    {
        return $this->status === 'pendente';
    }

    public function marcarComoUtilizada(): bool
    {
        if (! $this->podeSerUtilizada()) {
            return false;
        }

        return $this->update([
            'status' => 'utilizado',
            'datahora_efetiva_saida' => now(),
        ]);
    }

    public function cancelar(): bool
    {
        if ($this->status === 'utilizado') {
            return false;
        }

        return $this->update([
            'status' => 'cancelado',
        ]);
    }
}
