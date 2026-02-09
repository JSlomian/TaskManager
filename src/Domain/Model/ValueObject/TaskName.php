<?php

declare(strict_types=1);

namespace App\Domain\Model\ValueObject;

final class TaskName
{
    private string $value;

    public function __construct(string $value)
    {
        $trimmedValue = trim($value);
        if ('' === $trimmedValue) {
            throw new \InvalidArgumentException('Task name cannot be empty.');
        }
        $this->value = $trimmedValue;
    }

    public function value(): string
    {
        return $this->value;
    }
}
