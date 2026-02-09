<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\LoginUserCommand;
use App\Infrastructure\Doctrine\Repository\UserEntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class LoginUserHandler
{
    public function __construct(
        private UserEntityRepository $repository,
        private RequestStack $requestStack,
    ) {
    }

    public function __invoke(LoginUserCommand $command): void
    {
        $entity = $this->repository->find($command->userId());

        if (!$entity) {
            throw new \Exception('User not found');
        }
        $session = $this->requestStack->getSession();
        $session->set('user_id', $entity->getId());
    }
}
