<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vinculado extends Model
{
    use HasFactory;

    protected $table = 'vinculados';

    protected $fillable = [
        'responsavel_id',
        'vinculo_id',
        'nome',
        'nascimento',
        'lembrar',
    ];

    protected $casts = [
        'nascimento' => 'date',
        'lembrar' => 'boolean',
    ];

    // Relacionamentos
    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }

    public function vinculo()
    {
        return $this->belongsTo(Vinculo::class);
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }

    // Accessors
    public function getIdadeAttribute()
    {
        return Carbon::parse($this->nascimento)->age;
    }

    public function getTipoDescricaoAttribute()
    {
        return $this->vinculo ? $this->vinculo->descricao : 'Não definido';
    }

    // Métodos Auxiliares de Validação de Inscrição
    /**
     * Verifica se o responsável deste vinculado está inscrito e ativo em um evento
     */
    public function responsavelInscritoNoEvento(int $eventoId): bool
    {
        return $this->responsavel
            ->inscricoes()
            ->where('evento_id', $eventoId)
            ->where('ativo', true)
            ->exists();
    }

    /**
     * Obter inscrição do responsável em um evento
     */
    public function inscricaoDoResponsavelNoEvento(int $eventoId): ?Inscricao
    {
        return $this->responsavel
            ->inscricoes()
            ->where('evento_id', $eventoId)
            ->first();
    }

    // Scopes
    /**
     * Scope para filtrar vinculados cujos responsáveis estão inscritos em um evento
     */
    public function scopeComResponsavelInscritoNoEvento($query, int $eventoId)
    {
        return $query->whereHas('responsavel.inscricoes', function ($q) use ($eventoId) {
            $q->where('evento_id', $eventoId)->where('ativo', true);
        });
    }
}
