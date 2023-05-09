<?php

namespace App\Repository;

use App\Dto\CriteriaDto;
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

    public function save(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCriteria(?int $userId, CriteriaDto $criteriaDto): array
    {
        $q = $this->createQueryBuilder('t');

        if ($userId) {
            $q->andWhere('t.user = :val')
                ->setParameter('val', $userId);
        }

        if ($criteriaDto->getFilterByStatus()) {
            $q->andWhere('t.status = :status')
                ->setParameter('status', $criteriaDto->getFilterByStatus());
        }

        if ($criteriaDto->getFilterByDate()) {
            $q->andWhere('t.date > :from')
                ->setParameter('from', $criteriaDto->getFilterByDate());
        }

        $order = $criteriaDto->isSortASC() ? 'ASC' : 'DESC';
        $sortBy = $criteriaDto->getSortedBy();
        if ($sortBy == 'title') {
            $q->orderBy('t.title', $order);
        } elseif ($sortBy == 'date') {
            $q->orderBy('t.date', $order);
        } else {
            $q->orderBy('t.id', $order);
        }


        return $q->getQuery()->getResult();
    }
}
