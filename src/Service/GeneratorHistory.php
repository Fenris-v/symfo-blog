<?php

namespace App\Service;

use App\Dto\ArticleGeneratorDto;
use App\Entity\GeneratorHistory as HistoryEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GeneratorHistory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {
    }

    /**
     * Сохраняет статью в истории
     * @param string $article
     * @param ArticleGeneratorDto $articleGeneratorDto
     * @return HistoryEntity
     */
    public function save(string $article, ArticleGeneratorDto $articleGeneratorDto): HistoryEntity
    {
        $history = $this->getEntity($article, $articleGeneratorDto);

        $this->entityManager->persist($history);
        $this->entityManager->flush();

        return $history;
    }

    public function getEntity(string $article, ArticleGeneratorDto $articleGeneratorDto): HistoryEntity
    {
        $history = new HistoryEntity();
        $history->setArticle($article)
            ->setUser($this->security->getUser())
            ->setProps($articleGeneratorDto->toArray());

        return $history;
    }
}
