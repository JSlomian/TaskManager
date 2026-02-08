<?php

declare(strict_types=1);

namespace App\Domain\Model;

final readonly class User
{
    public function __construct(
        private int $id,
        private string $name,
        private string $username,
        private string $email,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function email(): string
    {
        return $this->email;
    }
}
