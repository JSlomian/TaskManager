<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Application\Command\LoginUserCommand;
use App\Application\Command\TaskCreateCommand;
use App\Application\Command\TaskUpdateCommand;
use App\Application\Command\UsersImportCommand;
use App\Domain\Model\ValueObject\TaskStatus;
use App\Infrastructure\Doctrine\Repository\UserEntityRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MutationResolver
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private UserEntityRepository $userRepository,
        private RequestStack $requestStack,
    ) {
    }

    public function importUsers(): bool
    {
        $this->messageBus->dispatch(new UsersImportCommand());

        return true;
    }

    public function login(int $userId): bool
    {
        $this->messageBus->dispatch(new LoginUserCommand($userId));

        return true;
    }

    public function taskCreate(Argument $args): bool
    {
        $args = $args->getArrayCopy();
        $this->messageBus->dispatch(
            new TaskCreateCommand($args['taskName'], $args['taskDescription'], $args['assignedUserId'])
        );

        return true;
    }

    public function taskUpdate(Argument $args): bool
    {
        $this->messageBus->dispatch(
            new TaskUpdateCommand($args['taskId'], TaskStatus::from($args['taskStatus']))
        );

        return true;
    }
}
