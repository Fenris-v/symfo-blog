<?php

namespace App\Controller\Dashboard;

use App\Entity\Subscription;
use App\Entity\User;
use App\Events\UpdateSubscriptionEvent;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Subscription as SubscriptionService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class SubscriptionController extends AbstractController
{
    /**
     * @param SubscriptionRepository $subscriptionRepository
     * @param Security $security
     * @param UserRepository $userRepository
     * @param SubscriptionService $subscriptionService
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/subscription', name: 'app_subscription')]
    public function index(
        SubscriptionRepository $subscriptionRepository,
        Security $security,
        UserRepository $userRepository,
        SubscriptionService $subscriptionService
    ): Response {
        /** @var User $user */
        $user = $security->getUser();
        $subscriptions = $subscriptionRepository->findAllSortedByPrice();

        if (!$subscriptionService->isActive($user)) {
            $userRepository->updateSubscription($user, $subscriptions[0]);
        }

        return $this->render(
            'subscription/index.html.twig',
            [
                'subscriptions' => $subscriptionRepository->findAllSortedByPrice(),
                'user' => $user
            ]
        );
    }

    /**
     * @param Subscription $subscription
     * @param UserRepository $userRepository
     * @param Security $security
     * @param SubscriptionService $subscriptionService
     * @param EventDispatcherInterface $eventDispatcher
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/subscription/{subscription}', name: 'app_subscription_update')]
    public function update(
        Subscription $subscription,
        UserRepository $userRepository,
        Security $security,
        SubscriptionService $subscriptionService,
        EventDispatcherInterface $eventDispatcher
    ) {
        /** @var User $user */
        $user = $security->getUser();

        $isUpdated = $subscriptionService
            ->updateSubscription($user, $userRepository, $subscription);


        if ($isUpdated) {
            $eventDispatcher->dispatch(new UpdateSubscriptionEvent($user, $subscription));
        }

        return $this->redirectToRoute('app_subscription');
    }
}
