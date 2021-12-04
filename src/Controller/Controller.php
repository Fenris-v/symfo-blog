<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Controller extends AbstractController
{
    protected function checkAuth(): ?RedirectResponse
    {
        return null;
    }
}
