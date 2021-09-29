<?php

namespace App\EventSubscriber;

use App\Events\UpdateSubscriptionEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UpdateSubscriptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private Mailer $mailer) {
    }

    /**
     * @param UpdateSubscriptionEvent $event
     * @throws TransportExceptionInterface
     */
    public function onUpdateSubscription(UpdateSubscriptionEvent $event)
    {
        $this->mailer->sendUpdateSubscriptionNotify($event->getUser(), $event->getSubscription());
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UpdateSubscriptionEvent::class => 'onUpdateSubscription',
        ];
    }
}
