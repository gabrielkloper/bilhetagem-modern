<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vinculo extends Model
{
    use HasFactory;

    protected $table = 'vinculos';

    protected $fillable = [
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // Relacionamentos
    public function vinculados()
    {
        return $this->hasMany(Vinculado::class);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeOrdenadosPorDescricao($query)
    {
        return $query->orderBy('descricao');
    }
}