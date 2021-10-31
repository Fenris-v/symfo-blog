<?php

namespace App\Service;

use App\Dto\ArticleGeneratorDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\GeneratorHistory as HistoryEntity;

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
     * @param ArticleGeneratorDto $dto
     */
    public function save(string $article, ArticleGeneratorDto $dto): void
    {
        $history = new HistoryEntity();
        $history->setArticle($article)
            ->setUser($this->security->getUser())
            ->setProps($dto->toArray());

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }
}
