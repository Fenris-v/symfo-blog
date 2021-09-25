<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TempController extends AbstractController
{
    #[Route('/temp', name: 'app_temp')]
    public function index(): Response
    {
        dump('You was authorized');

        return $this->render('base.html.twig');
    }
}
