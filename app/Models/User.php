<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'evento_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'status' => 'string'
        ];
    }

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function eventosAtualizados()
    {
        return $this->hasMany(Evento::class, 'user_atualiza');
    }

    public function caixaMovimentos()
    {
        return $this->hasMany(CaixaMovimento::class);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopePorRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    // Accessors e Mutators
    protected function isAtivo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'ativo',
        );
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->role === 'admin',
        );
    }

    protected function canLogin(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->status, ['ativo']),
        );
    }

    protected function roleLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->role) {
                'admin' => 'Administrador',
                'operador' => 'Operador',
                'caixa' => 'Caixa',
                'supervisor' => 'Supervisor',
                default => 'Usuário'
            },
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'ativo' => 'Ativo',
                'inativo' => 'Inativo',
                'suspenso' => 'Suspenso',
                'bloqueado' => 'Bloqueado',
                default => 'Indefinido'
            },
        );
    }

    // Métodos auxiliares
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    public function canAccessEvent($eventoId)
    {
        return $this->role === 'admin' || $this->evento_id === $eventoId;
    }
}
