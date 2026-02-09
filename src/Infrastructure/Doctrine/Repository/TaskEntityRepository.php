<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Model\Task;
use App\Infrastructure\Doctrine\Entity\TaskEntity;
use App\Infrastructure\Doctrine\Mapper\TaskMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskEntity>
 */
class TaskEntityRepository extends ServiceEntityRepository implements TasksRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskEntity::class);
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByAssignedUser(int $userId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.assignedUserId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function get(int $id): Task
    {
        $em = $this->getEntityManager();
        $entity = $em->find(TaskEntity::class, $id);

        return TaskMapper::toDomain($entity);
    }

    public function save(Task $task): void
    {
        $em = $this->getEntityManager();
        if (null === $task->taskId()) {
            $entity = TaskMapper::toNewEntity($task);
            $em->persist($entity);
            $em->flush();

            $task->setTaskId($entity->getId());
        } else {
            $entity = $em->find(TaskEntity::class, $task->taskId());
            TaskMapper::updateEntity($task, $entity);
            $em->flush();
        }
    }
}
