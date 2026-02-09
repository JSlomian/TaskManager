<?php

declare(strict_types=1);

namespace App\Application\Command;

final readonly class LoginUserCommand
{
    public function __construct(
        private int $userId,
    ) {
    }

    public function userId(): int
    {
        return $this->userId;
    }
}
