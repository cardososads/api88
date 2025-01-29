<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public readonly string  $name,
        public readonly string  $email,
        public readonly ?string $password = null, // Pode ser null no update
        public readonly ?string $group_id = null,
        public readonly ?string $cpf = null,
        public readonly ?string $mentor_id = null,
        public readonly array   $metadata = []
    ) {}

    public function toArray(): array
    {
        return [
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => $this->password,
            'group_id'   => $this->group_id,
            'cpf'        => $this->cpf,
            'mentor_id'  => $this->mentor_id,
            'metadata'   => $this->metadata,
        ];
    }
}
