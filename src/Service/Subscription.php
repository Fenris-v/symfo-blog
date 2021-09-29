<?php

namespace App\Service;

use App\Entity\Subscription as SubscriptionModel;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class Subscription
{
    public function __construct(
        private UserRepository $userRepository,
        private SubscriptionRepository $subscriptionRepository
    ) {
    }

    /**
     * Возвращает количество дней до конца подписки
     * @param User $user
     * @return int|null
     */
    public function getDaysForEndSubscription(User $user): ?int
    {
        if (!$user->getSubscriptionLeft()) {
            return null;
        }

        return (new DateTime())
            ->diff($user->getSubscriptionLeft())
            ->format('%R%a');
    }

    /**
     * Возвращает подписку
     * @param User $user
     * @return SubscriptionModel
     */
    public function getSubscription(User $user): SubscriptionModel
    {
        if ($user->getSubscription() && $this->isActive($user)) {
            return $this->subscriptionRepository
                ->findOneBy(['slug' => $user->getSubscription()]);
        }

        return $this->subscriptionRepository
            ->findOneBy(['slug' => SubscriptionModel::LEVELS['min']]);
    }

    /**
     * Можно ли обновить подписку
     * @param SubscriptionModel $subscription
     * @return bool
     */
    public function canUpdate(SubscriptionModel $subscription)
    {
        return $subscription->getSlug() !== SubscriptionModel::LEVELS['max'];
    }

    /**
     * Можно ли обновить подписку
     * @param User $user
     * @param SubscriptionModel $subscription
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateSubscription(User $user, SubscriptionModel $subscription): bool
    {
        if ($subscription->getPrice() <= $this->getSubscription($user)->getPrice()) {
            return false;
        }

        $this->userRepository->updateSubscriptionLevel(
            $user,
            $subscription->getSlug(),
            (new DateTime())->modify('+1 week')
        );

        return true;
    }

    /**
     * Проверяет является ли текущая подписка активной
     * @param User $user
     * @return bool
     */
    public function isActive(User $user): bool
    {
        return !$user->getSubscriptionLeft() || $user->getSubscriptionLeft() > new DateTime();
    }
}
