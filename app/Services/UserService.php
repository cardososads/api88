<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(UserDTO $dto): User
    {
        $data = $dto->toArray();
        $data['password'] = bcrypt($data['password']); // Hash da senha

        // Criação do usuário
        $user = User::create($data);

        // Se metadata for uma relação, salve-a
        if (!empty($dto->metadata)) {
            foreach ($dto->metadata as $key => $value) {
                $user->metadata()->create([
                    'key'   => $key,
                    'value' => $value,
                ]);
            }
        }

        return $user->load('group', 'metadata');
    }

    public function updateUser(User $user, UserDTO $dto): User
    {
        // Monta array parcial para update
        $data = [];

        // Se password veio null, não atualiza
        if ($dto->name !== $user->name) {
            $data['name'] = $dto->name;
        }
        if ($dto->email !== $user->email) {
            $data['email'] = $dto->email;
        }
        if ($dto->password) {
            $data['password'] = Hash::make($dto->password);
        }
        if ($dto->group_id) {
            $data['group_id'] = $dto->group_id;
        }
        if ($dto->cpf) {
            $data['cpf'] = $dto->cpf;
        }
        if ($dto->mentor_id) {
            $data['mentor_id'] = $dto->mentor_id;
        }

        $user->update($data);

        // Atualizar (ou criar) metadados
        if (!empty($dto->metadata)) {
            $this->syncMetadata($user, $dto->metadata);
        }

        return $user;
    }

    private function syncMetadata(User $user, array $metadata): void
    {
        foreach ($metadata as $key => $value) {
            $meta = $user->metadata()->where('key', $key)->first();
            if ($meta) {
                $meta->update(['value' => $value]);
            } else {
                $user->metadata()->create(['key' => $key, 'value' => $value]);
            }
        }
    }
}
