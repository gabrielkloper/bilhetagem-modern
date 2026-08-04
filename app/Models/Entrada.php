<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'evento_id',
        'vinculado_id',
        'pacote_id',
        'prevenda_id',
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
        'payment_status',
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
        'pacote_tolerancia' => 'integer',
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
            get: fn () => match ($this->status) {
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
            get: fn () => match ($this->tipo_entrada) {
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
            get: fn () => match ($this->forma_pagamento) {
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
            get: fn () => 'R$ '.number_format($this->valor_pago, 2, ',', '.')
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
        return ! in_array($this->status, ['finalizado', 'cancelado']);
    }

    public function podeSerCancelada(): bool
    {
        return ! in_array($this->status, ['finalizado', 'cancelado']);
    }

    public function registrarSaida(): array
    {
        if (! $this->podeRegistrarSaida()) {
            return [
                'success' => false,
                'message' => 'Entrada não pode ser finalizada.',
                'code' => 'NOT_ACTIVE',
            ];
        }

        $agora = now();
        $tempoPermanencia = $this->datahora_entrada->diffInMinutes($agora);

        // Calculate billing using existing Pacote method
        $valorTotalDevido = $this->pacote->calcularValorTotal($tempoPermanencia);
        $valorJaPago = $this->valor_pago ?? 0;
        $valorAdicional = max(0, $valorTotalDevido - $valorJaPago);

        // Determine if overage payment is required
        $tempoExcedido = 0;
        $limiteTempo = $this->pacote_duracao + ($this->pacote_tolerancia ?? 0);
        if ($tempoPermanencia > $limiteTempo) {
            $tempoExcedido = $tempoPermanencia - $limiteTempo;
        }

        // Check if overage payment was made
        if ($valorAdicional > 0 && ! $this->pgto_extra) {
            return [
                'success' => false,
                'message' => 'Pagamento adicional obrigatório',
                'code' => 'PAYMENT_REQUIRED',
                'data' => [
                    'tempo_permanencia' => $tempoPermanencia,
                    'tempo_excedido' => $tempoExcedido,
                    'valor_adicional' => $valorAdicional,
                    'valor_total_devido' => $valorTotalDevido,
                    'valor_ja_pago' => $valorJaPago,
                ],
            ];
        }

        // Update entrada with exit data
        $this->update([
            'status' => 'finalizado',
            'datahora_saida' => $agora,
            'tempo_permanencia' => $tempoPermanencia,
            'tempo_excedido' => $tempoExcedido,
        ]);

        return [
            'success' => true,
            'message' => 'Saída registrada com sucesso',
            'data' => [
                'tempo_permanencia' => $tempoPermanencia,
                'tempo_excedido' => $tempoExcedido,
                'valor_adicional' => $valorAdicional,
                'status' => 'finalizado',
            ],
        ];
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
