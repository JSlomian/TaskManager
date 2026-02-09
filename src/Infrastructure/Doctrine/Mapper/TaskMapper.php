<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\Model\Task;
use App\Domain\Model\ValueObject\TaskName;
use App\Domain\Model\ValueObject\TaskStatus;
use App\Infrastructure\Doctrine\Entity\TaskEntity;

final class TaskMapper
{
    public static function toDomain(TaskEntity $entity): Task
    {
        return Task::reconstitute(
            taskId: $entity->getId(),
            taskName: new TaskName($entity->getName()),
            taskStatus: TaskStatus::from($entity->getStatus()),
            taskDescription: $entity->getDescription(),
            assignedUserId: $entity->getAssignedUserId()
        );
    }

    public static function toNewEntity(Task $task): TaskEntity
    {
        return (new TaskEntity())
            ->setName($task->taskName()->value())
            ->setStatus($task->taskStatus()->value)
            ->setAssignedUserId($task->assignedUserId())
            ->setDescription($task->taskDescription());
    }

    public static function updateEntity(Task $task, TaskEntity $entity): void
    {
        $entity->setStatus($task->taskStatus()->value);
    }
}
