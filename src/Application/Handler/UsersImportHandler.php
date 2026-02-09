<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\UsersImportCommand;
use App\Domain\Model\User;
use App\Infrastructure\Doctrine\Repository\UserEntityRepository;
use App\Infrastructure\Http\JsonPlaceholderClient;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UsersImportHandler
{
    public function __construct(
        private JsonPlaceholderClient $client,
        private UserEntityRepository $userEntityRepository,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function __invoke(UsersImportCommand $command): void
    {
        $users = $this->client->fetchUsers();
        /**
         * @var iterable<User> $userObjects
         */
        $userObjects = [];
        foreach ($users as $userData) {
            $userObjects[] = User::create($userData['id'], $userData['name'], $userData['username'], $userData['email']);
        }
        $this->userEntityRepository->saveMany($userObjects);
    }
}
