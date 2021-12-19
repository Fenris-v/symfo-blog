<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Form\ModuleCreateFormType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ModulesController extends AbstractController
{
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/modules', name: 'app_modules')]
    public function index(
        ModuleRepository $moduleRepository,
        PaginatorInterface $paginator,
        Request $request,
        Security $security
    ): Response {
        /** @var ?User $user */
        $user = $security->getUser();

        $form = $this->createForm(ModuleCreateFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $moduleRepository->createFromFormModel($data, $user);

            $this->addFlash('module_added', 'Модуль успешно добавлен');
            return $this->redirectToRoute('app_modules');
        }

        $pagination = $paginator->paginate(
            $moduleRepository->getByUser($user->getId()),
            $request->query->getInt('page', 1),
            $request->query->get('perPage') ?? 3
        );

        return $this->render('dashboard/modules.html.twig', [
            'pagination' => $pagination,
            'moduleForm' => $form->createView()
        ]);
    }

    #[Route('/modules/{id}/delete', name: 'app_remove_module')]
    public function destroy(
        int $id,
        ModuleRepository $moduleRepository,
        Security $security
    ): RedirectResponse {
        $module = $moduleRepository->find($id);

        if ($module === null || $security->getUser() !== $module->getUser()) {
            return $this->redirectToRoute('app_modules');
        }

        $moduleRepository->remove($module);
        $this->addFlash('module_added', 'Модуль удален');

        return $this->redirectToRoute('app_modules');
    }
}
