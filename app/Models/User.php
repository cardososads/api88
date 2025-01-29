<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasUuids;

    /**
     * Se for trabalhar com UUID, é importante desativar o auto-incremento
     * e definir a keyType como string.
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Quais campos podem ser atribuídos em massa (mass assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'group_id',
        'cpf',
        'mentor_id'
    ];

    /**
     * Campos que devem ficar ocultos na serialização (JSON, etc.).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relacionamento com o 'Group' (grupo de acesso).
     * - Necessário ter uma tabela 'groups', com 'id' (UUID).
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relacionamento com 'UserMetadata' (tabela de metadados).
     * - Necessário ter uma tabela 'user_metadata', com user_id (UUID).
     */
    public function metadata()
    {
        return $this->hasMany(UserMetadata::class);
    }

    /**
     * Se cada usuário (cliente PF) tiver um mentor (outro User), podemos modelar assim:
     */
    public function mentor()
    {
        // 'mentor_id' é a fk em 'users' que aponta para outro 'users.id'
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
