<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    use HasFactory;

    protected $table = 'responsaveis';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone1',
        'telefone2',
        'nascimento'
    ];

    protected $casts = [
        'nascimento' => 'date',
    ];

    // Relacionamentos
    public function vinculados()
    {
        return $this->hasMany(Vinculado::class);
    }

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class);
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'inscricoes')->withPivot(['ativo', 'comunica', 'device_comunica', 'data_inscricao'])->withTimestamps();
    }

    public function entradas()
    {
        return $this->hasManyThrough(Entrada::class, Vinculado::class);
    }

    public function prevendas()
    {
        return $this->hasMany(Prevenda::class);
    }

    // Scopes
    public function scopePorEvento($query, $eventoId)
    {
        return $query->whereHas('inscricoes', function($q) use ($eventoId) {
            $q->where('evento_id', $eventoId);
        });
    }

    public function scopeAtivosNoEvento($query, $eventoId)
    {
        return $query->whereHas('inscricoes', function($q) use ($eventoId) {
            $q->where('evento_id', $eventoId)->where('ativo', true);
        });
    }

    public function scopeComVinculados($query)
    {
        return $query->has('vinculados');
    }

    public function scopeSemVinculados($query)
    {
        return $query->doesntHave('vinculados');
    }

    // Methods
    public function inscricaoNoEvento($eventoId)
    {
        return $this->inscricoes()->where('evento_id', $eventoId)->first();
    }

    public function estaInscritoNoEvento($eventoId)
    {
        return $this->inscricoes()->where('evento_id', $eventoId)->exists();
    }

    public function estaAtivoNoEvento($eventoId)
    {
        return $this->inscricoes()->where('evento_id', $eventoId)->where('ativo', true)->exists();
    }

    // Accessors
    public function getCpfFormattedAttribute()
    {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf);
    }
}
