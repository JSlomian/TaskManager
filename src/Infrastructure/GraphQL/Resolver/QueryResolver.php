<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Doctrine\Repository\TaskEntityRepository;
use App\Infrastructure\Doctrine\Repository\UserEntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class QueryResolver
{
    public function __construct(
        private UserEntityRepository $userEntityRepository,
        private TaskEntityRepository $taskEntityRepository,
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
        return $this->taskEntityRepository->findAll();
    }

    public function tasksByUser(int $userId): array
    {
        return $this->taskEntityRepository->findByAssignedUser($userId);
    }
}
