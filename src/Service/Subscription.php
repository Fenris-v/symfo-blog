<?php

namespace App\Service;

use App\Entity\Subscription as SubscriptionModel;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class Subscription
{
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
     * Можно ли обновить подписку
     * @param User $user
     * @param UserRepository $userRepository
     * @param SubscriptionModel $subscription
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateSubscription(
        User $user,
        UserRepository $userRepository,
        SubscriptionModel $subscription
    ): bool {
        if ($subscription->getPrice() > $user->getSubscription()->getPrice()) {
            $userRepository->updateSubscription($user, $subscription);
            return true;
        }

        return false;
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
