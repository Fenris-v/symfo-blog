<?php

namespace App\Controller\Dashboard;

use App\Entity\GeneratorHistory;
use App\Entity\User;
use App\Enums\Subscription;
use App\Form\ArticleCreateFormType;
use App\Repository\GeneratorHistoryRepository;
use App\Repository\ThemeRepository;
use App\Service\ArticleGenerator;
use App\Service\RestrictionService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Fenris\ThemeBundle\ThemeProvider;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ArticlesController extends AbstractController
{
    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    #[Route('/articles/create', name: 'app_article_create')]
    public function create(
        Request $request,
        ArticleGenerator $articleGenerator,
        ThemeRepository $themeRepository,
        RestrictionService $restrictionService,
        GeneratorHistoryRepository $generatorHistoryRepository,
        Security $security,
        ThemeProvider $someThemeProvider
    ): Response {
        $someThemeProvider->getThemes();
        $oldDataId = $request?->request?->get('articleId') ?? null;
        if ($oldDataId) {
            $oldData = $generatorHistoryRepository->getById($oldDataId)?->getProps();
        }

        $form = $this->createForm(ArticleCreateFormType::class);
        $form->handleRequest($request);
        /** @var ?User $user */
        $user = $security->getUser();

        $date = new DateTime();
        $date->modify(Subscription::TimeOfRestriction->value);
        $countArticlesByHour = $generatorHistoryRepository
            ->getArticlesCountAfterDateTime($user->getId(), $date);

        $limitIsOver = $restrictionService->canGenerate($countArticlesByHour, $user);

        $article = null;
        if (!$limitIsOver && $data = $request->request->get('article_create_form')) {
            /** @var array $data */
            $article = $articleGenerator->createDto($data)->getArticle();

            $articleGenerator->setRenderedText($this->get('twig'), $article);

            $countArticlesByHour++;
            $limitIsOver = $restrictionService->canGenerate($countArticlesByHour, $user);
        }

        return $this->render('dashboard/create_article.html.twig', [
            'limitIsOver' => $limitIsOver,
            'themes' => $themeRepository->findAll(),
            'articleForm' => $form->createView(),
            'dto' => $articleGenerator->getArticleGeneratorDto(),
            'article' => $article?->getArticle(),
            'title' => isset($article?->getProps()['title']) ? $article->getProps()['title'] : null,
            'oldData' => $oldData ?? null
        ]);
    }

    /**
     * @param GeneratorHistoryRepository $generatorHistoryRepository
     * @param Security $security
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param ArticleGenerator $articleGenerator
     * @return Response
     * @throws LoaderError
     * @throws SyntaxError
     */
    #[Route('/articles/history', name: 'app_article_history')]
    public function index(
        GeneratorHistoryRepository $generatorHistoryRepository,
        Security $security,
        PaginatorInterface $paginator,
        Request $request,
        ArticleGenerator $articleGenerator
    ): Response {
        /** @var User $user */
        $user = $security->getUser();
        $pagination = $paginator->paginate(
            $generatorHistoryRepository->getLatestArticles($user->getId()),
            $request->query->getInt('page', 1),
            $request->query->get('perPage') ?? 5
        );

        /** @var GeneratorHistory $item */
        foreach ($pagination as $item) {
            $articleGenerator->setRenderedText($this->get('twig'), $item);
        }

        return $this->render('dashboard/history.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param int $id
     * @param GeneratorHistoryRepository $historyRepository
     * @param ArticleGenerator $articleGenerator
     * @return Response
     * @throws LoaderError
     * @throws SyntaxError
     */
    #[Route('/articles/history/{id}', name: 'app_article_history_detail')]
    public function show(
        GeneratorHistoryRepository $historyRepository,
        ArticleGenerator $articleGenerator,
        int $id
    ): Response {
        $article = $historyRepository->getById($id);

        $articleGenerator->setRenderedText($this->get('twig'), $article);

        return $this->render('dashboard/history_detail.twig', [
            'article' => $article
        ]);
    }
}
