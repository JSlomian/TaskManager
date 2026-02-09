<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\TaskUpdateCommand;
use App\Domain\Strategy\TaskStatusTransitionStrategy;
use App\Infrastructure\Doctrine\Repository\TaskEntityRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class TaskUpdateHandler
{
    public function __construct(
        private TaskEntityRepository $taskEntityRepository,
        private TaskStatusTransitionStrategy $taskStatusTransitionStrategy,
    ) {
    }

    public function __invoke(TaskUpdateCommand $command): void
    {
        $task = $this->taskEntityRepository->get($command->taskId());

        $task->changeStatus($command->taskStatus(), $this->taskStatusTransitionStrategy);
        $this->taskEntityRepository->save($task);
    }
}
