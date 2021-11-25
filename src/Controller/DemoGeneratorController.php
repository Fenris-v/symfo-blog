<?php

namespace App\Controller;

use App\Dto\ArticleGeneratorDto;
use App\Service\ArticleGenerator;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DemoGeneratorController extends AbstractController
{
    /**
     * @param Request $request
     * @param ArticleGenerator $articleGenerator
     * @return Response
     * @throws NonUniqueResultException
     * @throws LoaderError
     * @throws SyntaxError
     */
    #[Route('/try', name: 'app_demo_generator')]
    public function index(
        Request $request,
        ArticleGenerator $articleGenerator
    ): Response {
        $generated = $request->cookies->get('generated', false);
        /** @var array $data */
        if (!$generated && $data = $request->request->get('demo')) {
            $this->setCookie();
            $generated = true;

            $articleDto = $this->createDto($data);

            $article = $articleGenerator->getArticle($articleDto);

            $article = $this->get('twig')
                ->createTemplate($article)
                ?->render(['keyword' => [$articleDto->getKeyword()]]);
        }

        return $this->render(
            'demo/index.html.twig',
            [
                'article' => $article ?? null,
                'disabled' => (bool)$generated
            ]
        );
    }

    private function createDto(array $data): ArticleGeneratorDto
    {
        $articleDto = new ArticleGeneratorDto();
        $articleDto->setTitle($data['title'] ?? null);
        $articleDto->setKeyword($data['keyword']);

        return $articleDto;
    }

    private function setCookie()
    {
        $response = new Response();
        $response->headers->setCookie(Cookie::create('generated', true));
        $response->send();
    }
}
