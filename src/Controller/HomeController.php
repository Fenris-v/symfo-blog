<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if(!$this->isGranted('IS_ANONYMOUS')) {
            // TODO: заменить маршрут
            return $this->redirectToRoute('app_temp');
        }

        return $this->render('home/index.html.twig');
    }
}
