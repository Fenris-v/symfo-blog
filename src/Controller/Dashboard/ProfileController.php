<?php

namespace App\Controller\Dashboard;

use App\Dto\ProfileDto;
use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use App\Service\ApiToken;
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
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request,
        Security $security,
        ApiToken $apiTokenService,
        UserRepository $userRepository
    ): Response {
        $form = $this->createForm(UserRegistrationFormType::class, options: ['block_name' => 'user_update']);
        $form->handleRequest($request);
        /** @var User $user */
        $user = $security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $profileDto = $this->createProfileDto($form->getData());
            $userRepository->updateUser($user, $profileDto);
            $this->addFlash('profile_updated', 'Профиль успешно изменен');
        }

        return $this->render('dashboard/profile.html.twig', [
            'isExpiredToken' => $apiTokenService->isExpired(),
            'token' => $apiTokenService->getToken() ?? null,
            'profileForm' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/profile/generate-token', name: 'app_profile_token_generate')]
    public function generateToken(ApiToken $apiTokenService): RedirectResponse
    {
        $apiTokenService->generateToken();
        $this->addFlash('profile_updated', 'Токен успешно сгенерирован');
        return $this->redirectToRoute('app_profile');
    }

    private function createProfileDto(UserRegistrationFormModel $data): ProfileDto
    {
        return new ProfileDto(
            $data->firstName,
            $data->email,
            $data->plainPassword ?? null
        );
    }
}
