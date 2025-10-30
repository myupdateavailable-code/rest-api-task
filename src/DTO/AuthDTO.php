<?php

declare(strict_types=1);

namespace App\DTO;

class AuthDTO
{

    private int $id;

    private string $email;

    private function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public static function create(int $id, string $email): AuthDTO
    {
        return new self($id, $email);
    }

    public function id(): int
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }
}