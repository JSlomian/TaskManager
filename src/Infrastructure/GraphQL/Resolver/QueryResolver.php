<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Doctrine\Repository\EventEntityRepository;
use App\Infrastructure\Doctrine\Repository\TaskEntityRepository;
use App\Infrastructure\Doctrine\Repository\UserEntityRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class QueryResolver
{
    public function __construct(
        private UserEntityRepository $userEntityRepository,
        private TaskEntityRepository $taskEntityRepository,
        private EventEntityRepository $eventEntityRepository,
        private RequestStack $requestStack,
    ) {
    }

    public function users(): array
    {
        return $this->userEntityRepository->findAll();
    }

    public function me(): array
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('user_id');
        if (null === $userId) {
            throw new \Exception('User not logged it');
        }
        $user = $this->userEntityRepository->findOneAsArray($userId);
        if (null === $user) {
            throw new \Exception('User not found');
        }

        return $user;
    }

    public function tasks(): array
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('user_id');

        // Niech id: 1 będzie adminem
        if (1 !== $userId) {
            throw new \Exception('User not an admin');
        }

        return $this->taskEntityRepository->findAll();
    }

    public function tasksByUser(int $userId): array
    {
        return $this->taskEntityRepository->findByAssignedUser($userId);
    }

    public function events(): array
    {
        return $this->eventEntityRepository->findAll();
    }

    public function eventsForTask(Argument $args): array
    {
        $args = $args->getArrayCopy();
        return $this->eventEntityRepository->findByAggregate($args['eventType'], $args['aggregateId']);
    }
}
