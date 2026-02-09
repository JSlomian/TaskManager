<?php

declare(strict_types=1);

namespace App\Domain\Strategy;

use App\Domain\Model\ValueObject\TaskStatus;

interface TaskStatusTransitionStrategy
{
    public function assertCanTransition(
        TaskStatus $from,
        TaskStatus $to,
    ): void;
}
