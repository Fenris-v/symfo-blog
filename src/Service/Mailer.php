<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Subscription as Subscription;
use Closure;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    public function __construct(
        private MailerInterface $mailer,
        private string $siteName,
        private string $emailForSend
    ) {
    }

    /**
     * Отправка уведомления об обновлении подписки
     * @param User $user
     * @param Subscription $subscription
     * @throws TransportExceptionInterface
     */
    public function sendUpdateSubscriptionNotify(User $user, Subscription $subscription)
    {
        $this->send(
            'email/update_subscription.html.twig',
            $user,
            'Обновление подписки',
            function (TemplatedEmail $email) use ($user, $subscription) {
                $email->context(['user' => $user, 'subscription' => $subscription]);
            }
        );
    }

    /**
     * Отправка письма для подтверждения регистрации
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationMessage(User $user)
    {
        $this->send(
            'email/confirmation.html.twig',
            $user,
            'Подтверждение регистрации',
            function (TemplatedEmail $email) use ($user) {
                $email->context(['user' => $user]);
            }
        );
    }

    /**
     * Отправка письма для повторной активации
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendReactivateMessage(User $user)
    {
        $this->send(
            'email/reactivate.html.twig',
            $user,
            'Повторная активация',
            function (TemplatedEmail $email) use ($user) {
                $email->context(['user' => $user]);
            }
        );
    }

    /**
     * Отправка письма для сброса пароля
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendPasswordResetMessage(User $user)
    {
        $this->send(
            'email/reset_password.html.twig',
            $user,
            'Сброс пароля',
            function (TemplatedEmail $email) use ($user) {
                $email->context(['user' => $user]);
            }
        );
    }

    /**
     * @param string $template
     * @param User $user
     * @param string $subject
     * @param Closure|null $callback
     * @throws TransportExceptionInterface
     */
    private function send(
        string $template,
        User $user,
        string $subject,
        Closure $callback = null
    ) {
        $email = new TemplatedEmail();

        $email
            ->from(new Address($this->emailForSend, $this->siteName))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->subject($subject)
            ->htmlTemplate($template);

        if ($callback) {
            $callback($email);
        }

        $this->mailer->send($email);
    }
}
