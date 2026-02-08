<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Application\Command\UsersImportCommand;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MutationResolver
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function importUsers(): bool
    {
        $this->messageBus->dispatch(new UsersImportCommand());

        return true;
    }
}
