<?php

namespace App\Repository;

use App\Entity\GeneratorHistory;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GeneratorHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeneratorHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeneratorHistory[]    findAll()
 * @method GeneratorHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneratorHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeneratorHistory::class);
    }

    /**
     * Возвращает количество созданных статей за последний час
     * @param DateTime $date
     * @param int $userId
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getArticlesCountAfterDateTime(DateTime $date, int $userId): int
    {
        return $this->createQueryBuilder('gh')
            ->andWhere('gh.user=:user')
            ->setParameter('user', $userId)
            ->select('count(gh.id)')
            ->andWhere('gh.createdAt>:date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
