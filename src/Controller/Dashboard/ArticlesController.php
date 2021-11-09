<?php

namespace App\Controller\Dashboard;

use App\Dto\ArticleGeneratorDto;
use App\Entity\User;
use App\Form\ArticleCreateFormType;
use App\Repository\GeneratorHistoryRepository;
use App\Repository\ThemeRepository;
use App\Service\ArticleGenerator;
use App\Service\RestrictionService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     * @param Request $request
     * @param ArticleGenerator $articleGenerator
     * @param ThemeRepository $themeRepository
     * @param RestrictionService $restrictionService
     * @param GeneratorHistoryRepository $generatorHistoryRepository
     * @param Security $security
     * @return Response
     * @throws LoaderError
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws SyntaxError
     */
    #[Route('/articles/create', name: 'app_article_create')]
    public function create(
        Request $request,
        ArticleGenerator $articleGenerator,
        ThemeRepository $themeRepository,
        RestrictionService $restrictionService,
        GeneratorHistoryRepository $generatorHistoryRepository,
        Security $security
    ): Response {
        $form = $this->createForm(ArticleCreateFormType::class);
        $form->handleRequest($request);
        /** @var ?User $user */
        $user = $security->getUser();

        $date = new DateTime();
        $date->modify('-1 hour');
        $countArticlesByHour = $generatorHistoryRepository
            ->getArticlesCountAfterDateTime($date, $user->getId());

        $limitIsOver = $restrictionService->canGenerate($countArticlesByHour, $user);

        /** @var array $data */
        if (!$limitIsOver && $data = $request->request->get('article_create_form')) {
            $dto = $this->createDto($data, $restrictionService);

            $article = $this->get('twig')
                ->createTemplate($articleGenerator->getArticle($dto))
                ?->render(['keyword' => $dto->getKeywordWithDeclination() ?? '']);

            $countArticlesByHour++;
            $limitIsOver = $restrictionService->canGenerate($countArticlesByHour, $user);
        }

        return $this->render('dashboard/create_article.html.twig', [
            'limitIsOver' => $limitIsOver,
            'themes' => $themeRepository->findAll(),
            'articleForm' => $form->createView(),
            'dto' => $dto ?? null,
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

    /**
     * @param array $data
     * @param RestrictionService $restrictionService
     * @return ArticleGeneratorDto
     */
    private function createDto(
        array $data,
        RestrictionService $restrictionService
    ): ArticleGeneratorDto {
        $dto = new ArticleGeneratorDto();
        $dto->setTheme(
            isset($data['theme']) && $data['theme'] ? $data['theme'] : null
        );
        $dto->setTitle(
            isset($data['title']) && $data['title'] ? $data['title'] : null
        );
        $dto->setKeyword(
            isset($data['keyword']) && $data['keyword'] ? $data['keyword'] : null
        );
        $dto->setSizeFrom(
            isset($data['sizeFrom']) && $data['sizeFrom'] ? $data['sizeFrom'] : null
        );
        $dto->setSizeTo(
            isset($data['sizeTo']) && $data['sizeTo'] ? $data['sizeTo'] : null
        );
        $dto->setWordField(
            isset($data['wordField']) && $data['wordField'] ? $data['wordField'] : null
        );
        $dto->setWordCountField(
            isset($data['wordCountField']) && $data['wordCountField'] ? $data['wordCountField'] : null
        );

        if ($restrictionService->canHaveDeclinations()) {
            $dto->setDeclination(
                isset($data['declination']) && $data['declination'] ? $data['declination'] : null
            );
        } else {
            $dto->setDeclination(null);
        }

        return $dto;
    }
}
