<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Enums\Subscription;
use App\Repository\GeneratorHistoryRepository;
use App\Service\ApiLogger;
use App\Service\ArticleGenerator;
use App\Service\RestrictionService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

class ArticleController extends AbstractController
{
    /**
     * @throws SyntaxError
     * @throws NonUniqueResultException
     * @throws LoaderError
     * @throws NoResultException
     */
    #[Route('/api/article/generate', name: 'api_article_generate')]
    public function index(
        Request $request,
        Security $security,
        LoggerInterface $apiLogger,
        RestrictionService $restrictionService,
        GeneratorHistoryRepository $generatorHistoryRepository,
        ArticleGenerator $articleGenerator,
        TranslatorInterface $translator
    ): JsonResponse {
        /** @var User|null $user */
        $user = $security->getUser();
        if ($user === null) {
            return (new ApiLogger($apiLogger))->log($request);
        }

        $date = new DateTime();
        $date->modify(Subscription::TimeOfRestriction->value);
        $countArticlesByHour = $generatorHistoryRepository->getArticlesCountAfterDateTime($user->getId(), $date);

        if ($restrictionService->canGenerate($countArticlesByHour, $user)) {
            return $this->json(['error' => $translator->trans('Limit')]);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['theme']) && !empty($data['theme']) && isset($data['keyword']) && !empty($data['keyword'])) {
            return $this->json($this->prepareResult($articleGenerator, $data));
        }

        return $this->json(['error' => $translator->trans('Empty fields')]);
    }

    /**
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    private function prepareResult(ArticleGenerator $articleGenerator, array $data): array
    {
        $article = $articleGenerator->createDto($data)->getArticle();
        $articleGenerator->setRenderedText($this->get('twig'), $article);

        $result = [];
        if (isset($article->getProps()['title'])) {
            $result['title'] = $article->getProps()['title'];
        }

        $result['text'] = $article->getArticle();
        $result['description'] = mb_strimwidth(strip_tags($result['text']), 0, 150, '...');
        return $result;
    }
}
