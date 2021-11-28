<?php

namespace App\Service;

use App\Entity\User;
use DateTime;
use App\Entity\ApiToken as Token;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;

class ApiToken
{
    private ?Token $token;
    private ?User $user;

    public function __construct(Security $security, private EntityManagerInterface $em)
    {
        $this->user = $security->getUser();

        if (!$this->user) {
            throw new AccessDeniedHttpException();
        }

        $this->token = $this->user->getApiToken();
    }

    public function getToken(): ?string
    {
        return $this->token?->getToken();
    }

    public function isExpired(): bool
    {
        if (!$this->token) {
            return false;
        }

        return $this->token->getExpiresAt() <= new DateTime();
    }

    public function generateToken(): void
    {
        if (!$this->token) {
            $this->createToken();
        }

        $this->token->setToken(sha1(uniqid('token')));
        $this->token->setExpiresAt(new DateTime('+1 week'));

        $this->em->persist($this->token);
        $this->em->flush();
    }

    private function createToken(): void
    {
        $this->token = new Token();
        $this->token->setUser($this->user);
    }
}
