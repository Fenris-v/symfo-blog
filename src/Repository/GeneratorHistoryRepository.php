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
     * @param int $userId
     * @param DateTime|null $date
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getArticlesCountAfterDateTime(int $userId, DateTime $date = null): int
    {
        $query = $this->createQueryBuilder('gh')
            ->andWhere('gh.user=:user')
            ->setParameter('user', $userId)
            ->select('count(gh.id)');

        if ($date) {
            $query->andWhere('gh.createdAt>:date')
                ->setParameter('date', $date);
        }

        return $query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $userId
     * @return GeneratorHistory|null
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getLastArticle(int $userId): ?GeneratorHistory
    {
        return $this->createQueryBuilder('gh')
                ->orderBy('gh.createdAt', 'DESC')
                ->andWhere('gh.user=:user')
                ->setParameter('user', $userId)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult() ?? null;
    }
}
