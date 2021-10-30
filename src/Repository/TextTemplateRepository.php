<?php

namespace App\Repository;

use App\Entity\TextTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @throws NonUniqueResultException
     */
    public function getRandom(): TextTemplate
    {
        return $this->createQueryBuilder('t')
            ->orderBy('RAND()')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Возвращает параграфы по теме в указанном количестве
     * @param int $count
     * @param int $themeId
     * @return int|mixed|string
     */
    public function getRandomParagraphs(int $count, int $themeId)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.theme=:theme')
            ->setParameter('theme', $themeId)
            ->orderBy('RAND()')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }
}
