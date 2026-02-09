<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Model\User;
use App\Infrastructure\Doctrine\Entity\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserEntity>
 */
class UserEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    /**
     * @param iterable<User> $users
     */
    public function saveMany(iterable $users): void
    {
        $em = $this->getEntityManager();
        foreach ($users as $user) {
            if ($this->find($user->id())) {
                continue;
            }
            $entity = (new UserEntity())
                ->setId($user->id())
                ->setName($user->name())
                ->setUsername($user->username())
                ->setEmail($user->email());
            $em->persist($entity);
        }
        $em->flush();
    }

    public function findOneAsArray(int $userId): ?array
    {
        $result = $this->createQueryBuilder('u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getArrayResult();

        return $result[0] ?? null;
    }
}
