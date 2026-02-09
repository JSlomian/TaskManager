<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Infrastructure\Doctrine\Entity\TaskEntity;

interface TasksRepositoryInterface
{
    /** @return TaskEntity[] */
    public function findByAssignedUser(int $userId): array;
}
