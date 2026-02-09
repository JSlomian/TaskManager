<?php

declare(strict_types=1);

namespace App\Domain\Strategy;

use App\Domain\Model\ValueObject\TaskStatus;

final class DefaultTaskWorkflowStrategy implements TaskStatusTransitionStrategy
{
    public function assertCanTransition(TaskStatus $from, TaskStatus $to): void
    {
        $allowed = match ($from) {
            TaskStatus::ToDo => [TaskStatus::InProgress],
            TaskStatus::InProgress => [TaskStatus::Done],
            TaskStatus::Done => [],
        };

        if (!in_array($to, $allowed, true)) {
            throw new \DomainException(sprintf('Cannot transition task status from %s to %s', $from->value, $to->value));
        }
    }
}
