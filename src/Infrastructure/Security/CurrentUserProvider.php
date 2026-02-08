<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\HttpFoundation\RequestStack;

final readonly class CurrentUserProvider
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function getUserId(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \RuntimeException('No current request');
        }

        $userId = $request->headers->get('X-USER-ID');

        if (!$userId) {
            throw new \RuntimeException('Missing X-USER-ID header');
        }

        return $userId;
    }
}
