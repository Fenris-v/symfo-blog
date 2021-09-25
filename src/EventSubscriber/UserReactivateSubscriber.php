<?php

namespace App\EventSubscriber;

use App\Events\UserReactivateEvent;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserReactivateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Mailer $mailer,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @param UserReactivateEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransportExceptionInterface
     */
    public function onUserReactivate(UserReactivateEvent $event)
    {
        $user = $event->getUser();
        $this->userRepository->updateConfirmationCode($user, true);

        $this->mailer->sendReactivateMessage($user);
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserReactivateEvent::class => 'onUserReactivate',
        ];
    }
}
