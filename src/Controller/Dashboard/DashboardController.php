<?php

namespace App\Controller\Dashboard;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Subscription as SubscriptionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        SubscriptionService $subscriptionService,
        Security $security,
        UserRepository $userRepository,
        SubscriptionRepository $subscriptionRepository
    ): Response {
        /** @var User $user */
        $user = $security->getUser();

        if (!$subscriptionService->isActive($user)) {
            $userRepository->updateSubscription(
                $user,
                $subscriptionRepository->findOneBy(['slug' => Subscription::FREE])
            );
        }

        return $this->render('dashboard/index.html.twig', [
            'daysForEnd' => $subscriptionService->getDaysForEndSubscription($user),
            'user' => $user,
            'canUpdate' => $user->getSubscription()->getSlug() !== Subscription::MAX_SUBSCRIPTION_LEVEL
        ]);
    }
}
