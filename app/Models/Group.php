<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
    ];

    /**
     * Relacionamento N:N com Permissions,
     * usando a tabela pivot 'group_permission'.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permission');
    }
}
