<?php

declare(strict_types=1);

namespace App\Application\Command;

final readonly class TaskCreateCommand
{
    public function __construct(
        private string $taskName,
        private string $taskDescription,
        private int $assignedUserId,
    ) {
    }

    public function taskName(): string
    {
        return $this->taskName;
    }

    public function taskDescription(): string
    {
        return $this->taskDescription;
    }

    public function assignedUserId(): int
    {
        return $this->assignedUserId;
    }
}
