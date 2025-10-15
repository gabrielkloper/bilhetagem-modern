<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'evento_id',
        'vinculado_id',
        'pacote_id',
        'prevenda_id',
        'perfil_acesso_id',
        'perfil_acesso',
        'status',
        'datahora_entrada',
        'datahora_saida',
        'tempo_excedido',
        'tempo_permanencia',
        'pgto_extra',
        'pgto_extra_valor',
        'ativo',
        'pacote_valor',
        'pacote_duracao',
        'pacote_valor_adicional',
        'pacote_qtde_compra',
        'pacote_minutos_compra',
        'pacote_tolerancia',
        'pacote_nome',
        'autorizado',
        'datahora_autorizacao',
        'motivo_cancela',
        'forma_pagamento',
        'valor_pago',
        'valor_troco',
        'observacoes_pagamento',
        'pagamento_confirmado',
        'forma_pagamento_extra',
        'payment_id',
        'payment_status'
    ];

    protected $casts = [
        'datahora_entrada' => 'datetime',
        'datahora_saida' => 'datetime', 
        'datahora_autorizacao' => 'datetime',
        'pacote_valor' => 'decimal:2',
        'pgto_extra_valor' => 'decimal:2',
        'pacote_valor_adicional' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'valor_troco' => 'decimal:2',
        'ativo' => 'boolean',
        'pgto_extra' => 'boolean',
        'autorizado' => 'boolean',
        'pagamento_confirmado' => 'boolean',
        'tempo_permanencia' => 'integer',
        'tempo_excedido' => 'integer',
        'pacote_duracao' => 'integer',
        'pacote_qtde_compra' => 'integer',
        'pacote_minutos_compra' => 'integer',
        'pacote_tolerancia' => 'integer'
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function vinculado()
    {
        return $this->belongsTo(Vinculado::class);
    }

    public function perfilAcesso()
    {
        return $this->belongsTo(PerfilAcesso::class, 'perfil_acesso_id');
    }

    public function responsavel()
    {
        return $this->hasOneThrough(Responsavel::class, Vinculado::class, 'id', 'id', 'vinculado_id', 'responsavel_id');
    }

    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }

    public function prevenda()
    {
        return $this->belongsTo(Prevenda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function autorizadoPor()
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    // Scopes
    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_entrada', $tipo);
    }

    // Accessors
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'ativo' => 'Presente',
                'finalizado' => 'Finalizado',
                'cancelado' => 'Cancelado',
                'excedido' => 'Tempo Excedido',
                default => ucfirst($this->status)
            }
        );
    }

    protected function tipoEntradaLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->tipo_entrada) {
                'individual' => 'Individual',
                'pacote' => 'Pacote',
                'prevenda' => 'Pré-venda',
                'cortesia' => 'Cortesia',
                default => ucfirst($this->tipo_entrada)
            }
        );
    }

    protected function formaPagamentoLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->forma_pagamento) {
                'dinheiro' => 'Dinheiro',
                'cartao' => 'Cartão',
                'pix' => 'PIX',
                'transferencia' => 'Transferência',
                'gratuito' => 'Gratuito',
                default => ucfirst($this->forma_pagamento)
            }
        );
    }

    protected function valorFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ ' . number_format($this->valor_pago, 2, ',', '.')
        );
    }

    protected function tempoPermanenciaTexto(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->datahora_saida && $this->datahora_entrada) {
                    $minutos = $this->datahora_entrada->diffInMinutes($this->datahora_saida);
                    $horas = intdiv($minutos, 60);
                    $minutosRestantes = $minutos % 60;
                    return "{$horas}h {$minutosRestantes}min";
                }
                
                if ($this->status === 'ativo') {
                    $minutos = $this->datahora_entrada->diffInMinutes(now());
                    $horas = intdiv($minutos, 60);
                    $minutosRestantes = $minutos % 60;
                    return "{$horas}h {$minutosRestantes}min (ativo)";
                }
                
                return 'N/A';
            }
        );
    }

    // Métodos auxiliares  
    public function podeRegistrarSaida(): bool
    {
        return $this->status === 'ativo';
    }

    public function podeSerEditada(): bool
    {
        return !in_array($this->status, ['finalizado', 'cancelado']);
    }

    public function podeSerCancelada(): bool
    {
        return !in_array($this->status, ['finalizado', 'cancelado']);
    }

    public function registrarSaida(): bool
    {
        if (!$this->podeRegistrarSaida()) {
            return false;
        }

        $agora = now();
        $tempoPermanencia = $this->datahora_entrada->diffInMinutes($agora);

        $this->update([
            'status' => 'finalizado',
            'datahora_saida' => $agora,
            'tempo_permanencia' => $tempoPermanencia,
        ]);

        return true;
    }

    public function calcularTempoPermanencia(): int
    {
        if ($this->datahora_saida && $this->datahora_entrada) {
            return $this->datahora_entrada->diffInMinutes($this->datahora_saida);
        }
        
        if ($this->status === 'ativo') {
            return $this->datahora_entrada->diffInMinutes(now());
        }
        
        return 0;
    }
}
