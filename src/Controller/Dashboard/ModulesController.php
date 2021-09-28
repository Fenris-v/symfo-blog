<?php

namespace App\Controller\Dashboard;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ModulesController extends AbstractController
{
    #[Route('/modules', name: 'app_modules')]
    public function index(): Response
    {
        return $this->render('base_dashboard.html.twig');
    }
}
