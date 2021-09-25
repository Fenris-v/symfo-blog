<?php

namespace App\EventSubscriber;

use App\Events\UserRegisteredEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    public function __construct(private Mailer $mailer)
    {
    }

    /**
     * @param UserRegisteredEvent $event
     * @throws TransportExceptionInterface
     */
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $this->mailer->sendConfirmationMessage($event->getUser());
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
        ];
    }
}
