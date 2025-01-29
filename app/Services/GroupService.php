<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Permission;
use Illuminate\Support\Str;

class GroupService
{
    /**
     * Cria um novo grupo (e opcionalmente vincula permissões).
     */
    public function createGroup(array $data): Group
    {
        // $data contém ['name' => '...', 'permissions' => [..]] etc.
        // Se estiver usando UUID manual, ver se precisa setar 'id' => Str::uuid().

        $group = Group::create([
            'name' => $data['name']
        ]);

        // Se quiser vincular permissões:
        if (!empty($data['permissions'])) {
            $group->permissions()->attach($data['permissions']);
        }

        return $group;
    }

    /**
     * Atualiza um grupo existente e suas permissões, se vier no request.
     */
    public function updateGroup(Group $group, array $data): Group
    {
        if (isset($data['name'])) {
            $group->name = $data['name'];
        }

        $group->update($data);

        // Se quiser atualizar permissões, exemplo:
        if (isset($data['permissions'])) {
            // Sincroniza (remove as antigas e insere as novas)
            $group->permissions()->sync($data['permissions']);
        }

        return $group;
    }
}
