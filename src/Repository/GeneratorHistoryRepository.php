<?php

namespace App\Repository;

use App\Entity\GeneratorHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
