<?php

namespace App\EventSubscriber;

use App\Events\UserPasswordResetEvent;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserPasswordResetSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Mailer $mailer,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @param UserPasswordResetEvent $event
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransportExceptionInterface
     */
    public function onUserPasswordReset(UserPasswordResetEvent $event)
    {
        $user = $event->getUser();
        $this->userRepository->updateConfirmationCode($user, true);

        $this->mailer->sendPasswordResetMessage($user);
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserPasswordResetEvent::class => 'onUserPasswordReset',
        ];
    }
}
