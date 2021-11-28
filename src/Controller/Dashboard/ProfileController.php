<?php

namespace App\Controller\Dashboard;

use App\Form\UserRegistrationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, Security $security): Response
    {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        $this->addFlash('profile_updated', 'Профиль успешно изменен');

        return $this->render('dashboard/profile.html.twig', [
            'token' => 'asft21sfsvzxvghk1912t1g12m',
            'profileForm' => $form->createView(),
            'user' => $security->getUser()
        ]);
    }
}
