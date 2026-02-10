<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\TaskCreateCommand;
use App\Domain\Model\Task;
use App\Domain\Model\ValueObject\TaskName;
use App\Infrastructure\Doctrine\Entity\EventEntity;
use App\Infrastructure\Doctrine\Repository\EventEntityRepository;
use App\Infrastructure\Doctrine\Repository\TaskEntityRepository;
use App\Infrastructure\Doctrine\Repository\UserEntityRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class TaskCreateHandler
{
    public function __construct(
        private TaskEntityRepository $taskEntityRepository,
        private UserEntityRepository $userEntityRepository,
        private EventEntityRepository $eventEntityRepository,
    ) {
    }

    public function __invoke(TaskCreateCommand $command): void
    {
        if (!$this->userEntityRepository->find($command->assignedUserId())) {
            throw new \DomainException('Assigned user does not exist');
        }
        $task = Task::create(
            new TaskName($command->taskName()),
            $command->taskDescription(),
            $command->assignedUserId()
        );
        $this->taskEntityRepository->save($task);
        $eventEntity = (new EventEntity())
            ->setAggregateType('task')
            ->setAggregateId($task->taskId())
            ->setEventType('task.create')
            ->setPayload([
                'taskName' => $task->taskName(),
                'taskDescription' => $task->taskDescription(),
                'assignedUserId' => $task->assignedUserId(),
            ]);
        $this->eventEntityRepository->save($eventEntity);
    }
}
