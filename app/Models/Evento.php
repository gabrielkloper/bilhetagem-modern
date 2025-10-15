<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'local',
        'cidade',
        'endereco',
        'data_inicio',
        'data_fim',
        'hora_inicio',
        'hora_fim',
        'inicio',
        'fim',
        'capacidade',
        'preco_padrao',
        'status',
        'ativo',
        'publico',
        'permite_prevenda',
        'idade_minima',
        'idade_maxima',
        'aceita_dinheiro',
        'aceita_cartao',
        'aceita_pix',
        'aceita_gratuito',
        'hash',
        'regras_home',
        'regras_cadastro',
        'regras_parque',
        'msg_fimreserva',
        'tempo_atualiza',
        'mostra_tempo',
        'tempo_tela',
        'last_atualiza',
        'user_atualiza',
        'user_criacao',
        'regras_comunica',
        'observacoes',
        'timezone'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'inicio' => 'datetime',
        'fim' => 'datetime',
        'last_atualiza' => 'datetime',
        'status' => 'string',
        'ativo' => 'boolean',
        'mostra_tempo' => 'boolean',
        'publico' => 'boolean',
        'permite_prevenda' => 'boolean',
        'aceita_dinheiro' => 'boolean',
        'aceita_cartao' => 'boolean',
        'aceita_pix' => 'boolean',
        'aceita_gratuito' => 'boolean',
        'capacidade' => 'integer',
        'preco_padrao' => 'decimal:2',
        'tempo_atualiza' => 'integer',
        'tempo_tela' => 'integer'
    ];

    // Relacionamentos
    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class);
    }

    public function responsaveis()
    {
        return $this->belongsToMany(Responsavel::class, 'inscricoes')->withPivot(['ativo', 'comunica', 'device_comunica', 'data_inscricao'])->withTimestamps();
    }

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }

    public function prevendas()
    {
        return $this->hasMany(Prevenda::class);
    }

    public function caixaMovimentos()
    {
        return $this->hasMany(CaixaMovimento::class);
    }

    public function userAtualiza()
    {
        return $this->belongsTo(User::class, 'user_atualiza');
    }

    public function userCriacao()
    {
        return $this->belongsTo(User::class, 'user_criacao');
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopePorCidade($query, $cidade)
    {
        return $query->where('cidade', $cidade);
    }

    // Accessors
    protected function isAtivo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'ativo',
        );
    }

    protected function temCapacidadeDisponivel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->entradas()->where('status', 'ativo')->count() < $this->capacidade,
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'ativo' => 'Ativo',
                'inativo' => 'Inativo',
                'cancelado' => 'Cancelado',
                'finalizado' => 'Finalizado',
                default => ucfirst($this->status)
            }
        );
    }

    protected function modosPagemento(): Attribute
    {
        return Attribute::make(
            get: fn () => collect([
                'dinheiro' => $this->aceita_dinheiro,
                'cartao' => $this->aceita_cartao,
                'pix' => $this->aceita_pix,
                'gratuito' => $this->aceita_gratuito
            ])->filter()->keys()->toArray()
        );
    }

    protected function modosPagementoTexto(): Attribute
    {
        return Attribute::make(
            get: fn () => collect([
                'dinheiro' => $this->aceita_dinheiro ? 'Dinheiro' : null,
                'cartao' => $this->aceita_cartao ? 'Cartão' : null,
                'pix' => $this->aceita_pix ? 'PIX' : null,
                'gratuito' => $this->aceita_gratuito ? 'Gratuito' : null
            ])->filter()->values()->implode(', ')
        );
    }

    public function deveSerDesativado(): bool
    {
        return $this->ativo && 
               $this->data_fim && 
               $this->data_fim->isPast();
    }

    public function gerarHash(): string
    {
        return strtoupper(substr(md5($this->titulo . time() . rand()), 0, 10));
    }
}
