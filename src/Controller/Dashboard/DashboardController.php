<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
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
    /**
     * @param SubscriptionService $subscriptionService
     * @param Security $security
     * @return Response
     */
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(SubscriptionService $subscriptionService, Security $security): Response
    {
        /** @var User $user */
        $user = $security->getUser();
        $subscription = $subscriptionService->getSubscription($user);

        return $this->render('dashboard/index.html.twig', [
            'daysForEnd' => $subscriptionService->getDaysForEndSubscription($user),
            'user' => $user,
            'canUpdate' => $subscriptionService->canUpdate($subscription),
            'subscription' => $subscription
        ]);
    }
}
