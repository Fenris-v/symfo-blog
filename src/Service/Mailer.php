<?php

namespace App\Service;

use App\Entity\User;
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
