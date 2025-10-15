<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    use HasFactory;

    protected $table = 'inscricoes';

    protected $fillable = [
        'responsavel_id',
        'evento_id',
        'ativo',
        'comunica',
        'device_comunica',
        'data_inscricao'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'comunica' => 'boolean',
        'data_inscricao' => 'datetime',
    ];

    // Relacionamentos
    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    // Scopes
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    // Accessors
    public function getStatusAttribute()
    {
        return $this->ativo ? 'Ativa' : 'Inativa';
    }
}