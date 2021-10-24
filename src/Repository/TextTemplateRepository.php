<?php

namespace App\Repository;

use App\Entity\TextTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TextTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextTemplate[]    findAll()
 * @method TextTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextTemplate::class);
    }

    /**
     * @return TextTemplate
     */
    public function getRandom(): TextTemplate
    {
        return $this->createQueryBuilder('t')
            ->addSelect('t')
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];
    }
}
