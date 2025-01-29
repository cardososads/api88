<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
    ];

    /**
     * Relacionamento N:N com Groups.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_permission');
    }
}
