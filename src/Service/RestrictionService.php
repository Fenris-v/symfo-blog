<?php

namespace App\Service;

use App\Entity\User;
use App\Enums\Subscription;
use App\Service\Subscription as SubscriptionService;
use Symfony\Component\Security\Core\Security;

class RestrictionService
{
    private ?User $user;
    private ?string $subscriptionSlug;

    public function __construct(Security $security, SubscriptionService $subscriptionService)
    {
        $this->user = $security->getUser();
        $this->subscriptionSlug = $subscriptionService->getSubscription($this->user)?->getSlug();
    }

    /**
     * @return int|false
     */
    public function getMaxCountArticleByHour(): int|false
    {
        return match ($this->subscriptionSlug) {
            Subscription::Pro->value => false,
            Subscription::Plus->value, Subscription::Free->value => 2,
        };
    }

    /**
     * @return bool
     */
    public function canHaveDeclinations(): bool
    {
        return match ($this->subscriptionSlug) {
            Subscription::Free->value => false,
            Subscription::Plus->value, Subscription::Pro->value => true,
        };
    }
}
