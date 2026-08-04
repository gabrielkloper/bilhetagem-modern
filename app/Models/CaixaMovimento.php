<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaMovimento extends Model
{
    use HasFactory;

    protected $table = 'caixa_movimentos';

    protected $fillable = [
        'evento_id',
        'user_id',
        'tipo_despesa_id',
        'data_caixa',
        'tipo_item',
        'descricao',
        'valor',
        'ativo',
        'usuario_exclusao',
        'datahora_exclusao',
        'caixa_abertura_id',
    ];

    protected $casts = [
        'datahora_insercao' => 'datetime',
        'data_caixa' => 'date',
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
        'datahora_exclusao' => 'datetime',
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tipoDespesa()
    {
        return $this->belongsTo(TipoDespesa::class);
    }

    public function caixaAbertura()
    {
        return $this->belongsTo(CaixaAbertura::class);
    }

    public function usuarioExclusao()
    {
        return $this->belongsTo(User::class, 'usuario_exclusao');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    public function scopePorData($query, $data)
    {
        return $query->whereDate('data_caixa', $data);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_item', $tipo);
    }

    public function scopeEntradas($query)
    {
        return $query->where('tipo_item', 'entrada');
    }

    public function scopeSaidas($query)
    {
        return $query->where('tipo_item', 'saida');
    }

    public function scopeAjustes($query)
    {
        return $query->where('tipo_item', 'ajuste');
    }

    // Accessors
    protected function tipoItemLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->tipo_item) {
                'entrada' => 'Entrada',
                'saida' => 'Saída',
                'ajuste' => 'Ajuste',
                default => ucfirst($this->tipo_item)
            }
        );
    }

    protected function valorFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'R$ '.number_format($this->valor, 2, ',', '.'),
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ativo ? 'Ativo' : 'Excluído',
        );
    }

    // Métodos auxiliares
    public function softDelete($userId): bool
    {
        $this->ativo = false;
        $this->usuario_exclusao = $userId;
        $this->datahora_exclusao = now();

        return $this->save();
    }

    public function isExcluido(): bool
    {
        return ! $this->ativo;
    }

    public function podeSerEditado(): bool
    {
        return $this->ativo;
    }

    public static function calcularSaldo($eventoId, $data): float
    {
        $entradas = self::where('evento_id', $eventoId)
            ->whereDate('data_caixa', $data)
            ->where('ativo', true)
            ->where('tipo_item', 'entrada')
            ->sum('valor');

        $saidas = self::where('evento_id', $eventoId)
            ->whereDate('data_caixa', $data)
            ->where('ativo', true)
            ->where('tipo_item', 'saida')
            ->sum('valor');

        $ajustes = self::where('evento_id', $eventoId)
            ->whereDate('data_caixa', $data)
            ->where('ativo', true)
            ->where('tipo_item', 'ajuste')
            ->sum('valor');

        return (float) ($entradas - $saidas + $ajustes);
    }
}
