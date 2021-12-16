<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function getByUser(int $userId, int $limit = 3): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->where('m.user=:user')
            ->setParameter('user', $userId)
            ->setMaxResults($limit);
    }

    public function getFromAccessibleModules(int $length, ?int $userId = null): array
    {
        $builder = $this->createQueryBuilder('m')
            ->where('m.user is NULL');

        if ($userId !== null) {
            $builder->where('m.user=:user_id')
                ->setParameter('user_id', $userId);
        }

        return $builder->setMaxResults($length)
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult();
    }
}
