<?php

namespace App\Controller\Dashboard;

use App\Entity\Theme;
use App\Form\ArticleCreateFormType;
use App\Repository\ThemeRepository;
use App\Service\ArticleGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ArticlesController extends AbstractController
{
    /**
     * @param Request $request
     * @param ArticleGenerator $articleGenerator
     * @param ThemeRepository $themeRepository
     * @return Response
     */
    #[Route('/articles/create', name: 'app_article_create')]
    public function create(
        Request $request,
        ArticleGenerator $articleGenerator,
        ThemeRepository $themeRepository
    ): Response {
        $form = $this->createForm(ArticleCreateFormType::class);
        $form->handleRequest($request);

        if ($request->request->all()) {
            $data = $request->request->get('article_create_form');
            $article = $articleGenerator->getArticle($data ?? []);

            $article = $this->get('twig')
                ->createTemplate($article)
                ?->render(['keyword' => $data['keyword']]);
        }

        return $this->render('dashboard/create_article.html.twig', [
            'isLimitEnded' => true,
            'themes' => $themeRepository->findAll(),
            'articleForm' => $form->createView(),
            'keyword' => $data['keyword'] ?? [],
            'article' => $article ?? null
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/articles/history', name: 'app_article_history')]
    public function index(): Response
    {
        return $this->render('base_dashboard.html.twig');
    }
}
