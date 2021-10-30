<?php

namespace App\Service;

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
     * @param array $data
     */
    public function save(string $article, array $data = [])
    {
        $history = new HistoryEntity();
        $history->setArticle($article)
            ->setUser($this->security->getUser())
            ->setProps($data);

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }
}
