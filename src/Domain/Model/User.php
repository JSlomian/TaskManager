<?php

declare(strict_types=1);

namespace App\Domain\Model;

final readonly class User
{
    private function __construct(
        private int $id,
        private string $name,
        private string $username,
        private string $email,
    ) {
    }

    public static function create($id, $name, $username, $email): self
    {
        return new self($id, $name, $username, $email);
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
