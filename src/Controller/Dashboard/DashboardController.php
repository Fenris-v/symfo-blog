<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Repository\GeneratorHistoryRepository;
use App\Service\Subscription as SubscriptionService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class DashboardController extends AbstractController
{
    /**
     * @param SubscriptionService $subscriptionService
     * @param Security $security
     * @param GeneratorHistoryRepository $generatorHistoryRepository
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws LoaderError
     * @throws SyntaxError
     */
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        SubscriptionService $subscriptionService,
        Security $security,
        GeneratorHistoryRepository $generatorHistoryRepository
    ): Response {
        /** @var User $user */
        $user = $security->getUser();
        $subscription = $subscriptionService->getSubscription($user);

        $lastArticle = $generatorHistoryRepository->getLastArticle($user->getId());
        if ($lastArticle) {
            $keywords = [];
            if ($lastArticle->getProps()['declination']) {
                $keywords = $lastArticle->getProps()['declination'];
            }

            array_unshift($keywords, $lastArticle->getProps()['keyword']);

            $article = $this->get('twig')
                ->createTemplate($lastArticle->getArticle())
                ?->render(['keyword' => $keywords]);
        }

        $countGeneratedArticles = $generatorHistoryRepository
            ->getArticlesCountAfterDateTime($user->getId());

        $date = new DateTime();
        $date->modify('-1 month');
        $countGeneratedArticlesByMonth = $generatorHistoryRepository
            ->getArticlesCountAfterDateTime($user->getId(), $date);

        return $this->render('dashboard/index.html.twig', [
            'daysForEnd' => $subscriptionService->getDaysForEndSubscription($user),
            'user' => $user,
            'canUpdate' => $subscriptionService->canUpdate($subscription),
            'subscription' => $subscription,
            'article' => $article ?? '',
            'countGeneratedArticles' => $countGeneratedArticles,
            'countGeneratedArticlesByMonth' => $countGeneratedArticlesByMonth
        ]);
    }
}
