<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vinculado extends Model
{
    use HasFactory;

    protected $table = 'vinculados';

    protected $fillable = [
        'responsavel_id',
        'vinculo_id',
        'nome',
        'nascimento',
        'lembrar'
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
}
