<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Prevenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'evento_id',
        'responsavel_id',
        'valor',
        'quantidade',
        'status',
        'data_venda',
        'data_utilizacao',
        'observacoes',
        'user_id'
    ];

    protected $casts = [
        'data_venda' => 'datetime',
        'data_utilizacao' => 'datetime', 
        'valor' => 'decimal:2',
        'quantidade' => 'integer'
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

    public function user()
    {
        return $this->belongsTo(User::class);
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
            get: fn () => match($this->status) {
                'pendente' => 'Pendente',
                'utilizada' => 'Utilizada',
                'cancelada' => 'Cancelada',
                'expirada' => 'Expirada',
                default => ucfirst($this->status)
            }
        );
    }

    protected function valorFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ ' . number_format($this->valor, 2, ',', '.')
        );
    }

    // Métodos auxiliares
    public function podeSerUtilizada(): bool
    {
        return $this->status === 'pendente';
    }

    public function marcarComoUtilizada(): bool
    {
        if (!$this->podeSerUtilizada()) {
            return false;
        }

        return $this->update([
            'status' => 'utilizada',
            'data_utilizacao' => now()
        ]);
    }

    public function cancelar(): bool
    {
        if ($this->status === 'utilizada') {
            return false;
        }

        return $this->update([
            'status' => 'cancelada'
        ]);
    }
}
