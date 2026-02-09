<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\ValueObject\TaskStatus;

final readonly class TaskUpdateCommand
{
    public function __construct(
        private int $taskId,
        private TaskStatus $taskStatus,
    ) {
    }

    public function taskId(): int
    {
        return $this->taskId;
    }

    public function taskStatus(): TaskStatus
    {
        return $this->taskStatus;
    }
}
