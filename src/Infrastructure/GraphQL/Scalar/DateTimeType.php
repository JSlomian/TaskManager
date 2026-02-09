<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Scalar;

class DateTimeType
{
    public static function serialize(\DateTimeInterface $value): string
    {
        return $value->format(\DateTime::ATOM);
    }

    public static function parseValue(string $value): \DateTime
    {
        return new \DateTime($value);
    }

    public static function parseLiteral($valueNode): \DateTime
    {
        return new \DateTime($valueNode->value);
    }
}
