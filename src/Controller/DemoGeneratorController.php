<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoGeneratorController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/try', name: 'app_demo_generator')]
    public function index(): Response
    {
        return $this->render(
            'demo/index.html.twig',
            [
                'article' => null,
                'disabled' => true
            ]
        );
    }
}
