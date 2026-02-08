<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class JsonPlaceholderClient
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function fetchUsers(): array
    {
        $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/users');
        $statusCode = $response->getStatusCode();
        if (200 !== $statusCode) {
            throw new \RuntimeException(sprintf('Failed to fetch users, status code %d'), $statusCode);
        }
        $content = $response->getContent();
        $data = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        return $data;
    }
}
