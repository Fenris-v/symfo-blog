<?php

namespace App\Controller\Dashboard;

use App\Form\ArticleCreateFormType;
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
     * @return Response
     */
    #[Route('/articles/create', name: 'app_article_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(ArticleCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
        }

        return $this->render('dashboard/create_article.html.twig', [
            'article' => $article ?? null,
            'isLimitEnded' => true,
            'articleForm' => $form->createView()
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
