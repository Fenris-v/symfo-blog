<?php

namespace App\Controller\Dashboard;

use App\Repository\ModuleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ModulesController extends AbstractController
{
    #[Route('/modules', name: 'app_modules')]
    public function index(
        ModuleRepository $moduleRepository,
        PaginatorInterface $paginator,
        Request $request,
        Security $security
    ): Response {
        $pagination = $paginator->paginate(
            $moduleRepository->getByUser($security->getUser()->getId()),
            $request->query->getInt('page', 1),
            $request->query->get('perPage') ?? 3
        );

        $this->addFlash('module_added', 'Модуль успешно добавлен');

        return $this->render('dashboard/modules.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
