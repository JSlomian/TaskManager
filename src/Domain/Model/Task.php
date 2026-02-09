<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\ValueObject\TaskName;
use App\Domain\Model\ValueObject\TaskStatus;
use App\Domain\Strategy\TaskStatusTransitionStrategy;

final class Task
{
    private function __construct(
        private ?int $taskId = null,
        private readonly TaskName $taskName,
        private TaskStatus $taskStatus,
        private readonly string $taskDescription,
        private readonly int $assignedUserId,
    ) {
    }

    public static function create(TaskName $taskName, string $taskDescription, int $assignedUserId): self
    {
        return new self(null, $taskName, TaskStatus::ToDo, $taskDescription, $assignedUserId);
    }

    public static function reconstitute(
        int $taskId,
        TaskName $taskName,
        TaskStatus $taskStatus,
        string $taskDescription,
        int $assignedUserId,
    ): self {
        return new self($taskId, $taskName, $taskStatus, $taskDescription, $assignedUserId);
    }

    public function taskId(): ?int
    {
        return $this->taskId;
    }

    public function setTaskId(?int $taskId): self
    {
        if (null !== $this->taskId) {
            throw new \LogicException('Cannot set task twice');
        }
        $this->taskId = $taskId;

        return $this;
    }

    public function taskName(): TaskName
    {
        return $this->taskName;
    }

    public function taskStatus(): TaskStatus
    {
        return $this->taskStatus;
    }

    public function taskDescription(): string
    {
        return $this->taskDescription;
    }

    public function assignedUserId(): ?int
    {
        return $this->assignedUserId;
    }

    public function changeStatus(
        TaskStatus $newStatus,
        TaskStatusTransitionStrategy $strategy,
    ): void {
        $strategy->assertCanTransition($this->taskStatus, $newStatus);
        $this->taskStatus = $newStatus;

        // TODO: Event
    }
}
