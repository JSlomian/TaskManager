<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Doctrine\Repository\UserEntityRepository;

final class QueryResolver
{
    public function __construct(private readonly UserEntityRepository $userEntityRepository)
    {
    }

    public function users(): array
    {
        return $this->userEntityRepository->findAll();
    }

    public function me(): array
    {
        return [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
        ];
    }
}
