<?php

namespace App\Controller;

use App\Events\UserRegisteredEvent;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    /**
     * Аутентификация
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'errors' => $error,
            ]
        );
    }

    /**
     * Выход
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    /**
     * Регистрация
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher
    ): ?Response {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->createUser($form->getData(), $passwordHasher);

            $eventDispatcher->dispatch(new UserRegisteredEvent($user));
            $this->addFlash(
                'flash_register',
                'Для завершения регистрации подтвердите ваш email'
            );

            return $this->render('security/register.html.twig', [
                'registerForm' => $form->createView()
            ]);
        }

        return $this->render('security/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }

    /**
     * Подтверждение регистрации
     * @param Request $request
     * @param UserRepository $userRepository
     * @param string $code
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @return Response|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/confirm/{code}', name: 'app_confirm')]
    public function confirmEmail(
        Request $request,
        UserRepository $userRepository,
        string $code,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $loginFormAuthenticator,
    ): ?Response {
        $user = $userRepository->confirm($code);

        if ($user === null) {
            return new Response('404');
        }

        return $userAuthenticator->authenticateUser(
            $user,
            $loginFormAuthenticator,
            $request
        );
    }
}
