<?php

namespace App\Infrastructure\GraphQL\Resolver;

final class QueryResolver
{
    public function users(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@gmail.com',
            ],
        ];
    }
}
