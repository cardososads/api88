<?php

namespace App\Services;

use App\Models\Permission;

class PermissionService
{
    public function createPermission(array $data): Permission
    {
        return Permission::create([
            'key' => $data['key'],
        ]);
    }

    public function updatePermission(Permission $permission, array $data): Permission
    {
        if (isset($data['key'])) {
            $permission->key = $data['key'];
        }
        $permission->save();

        return $permission;
    }
}
