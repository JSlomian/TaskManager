<?php

declare(strict_types=1);

namespace App\Domain\Model\ValueObject;

enum TaskStatus: string
{
    case ToDo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';
}
