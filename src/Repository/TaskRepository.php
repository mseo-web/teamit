<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findPaginated(int $page, int $limit, ?string $status = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if ($status) {
            $qb->andWhere('t.status = :status')
               ->setParameter('status', $status);
        }

        return $qb->getQuery()
            ->getResult();
    }
}
