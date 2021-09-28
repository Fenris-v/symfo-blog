<?php

namespace App\Events;

use App\Entity\Subscription;
use App\Entity\User;

class UpdateSubscriptionEvent
{
    public function __construct(private User $user, private Subscription $subscription)
    {
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Subscription
     */
    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }
}
