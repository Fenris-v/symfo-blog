<?php

namespace App\Controller;

use App\Events\UserPasswordResetEvent;
use App\Events\UserReactivateEvent;
use App\Events\UserRegisteredEvent;
use App\Form\UserPasswordResetFormType;
use App\Form\UserRegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * Повторная активация
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    #[Route('/reactivate', name: 'app_reactivate')]
    public function reactivate(
        Request $request,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        try {
            $email = $request->request->get('email');
            $this->emailValidation($validator, $email);
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                throw new Exception("Пользователь $email не найден");
            }

            if ($user->getIsActive()) {
                throw new Exception("Пользователь $email уже активирован");
            }

            $eventDispatcher->dispatch(new UserReactivateEvent($user));

            $this->addFlash(
                'flash_reactivate',
                'На почту было отправлено письмо'
            );
        } catch (Exception $exception) {
        } finally {
            return $this->render(
                'security/reactivate.html.twig',
                ['errors' => isset($exception) ? $exception->getMessage() : null]
            );
        }
    }

    /**
     * Страница запроса сброса пароля
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    #[Route('/reset', name: 'app_reset_password')]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        try {
            $email = $request->request->get('email');
            $this->emailValidation($validator, $email);
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                throw new Exception("Пользователь $email не найден");
            }

            $eventDispatcher->dispatch(new UserPasswordResetEvent($user));

            $this->addFlash(
                'flash_password_reset',
                'На почту было отправлено письмо'
            );
        } catch (Exception $exception) {
        } finally {
            return $this->render(
                'security/reset_password.html.twig',
                ['errors' => isset($exception) ? $exception->getMessage() : null]
            );
        }
    }

    /**
     * Страница сброса пароля
     */
    #[Route('/reset/{code}', name: 'app_change_password')]
    public function changePassword(
        string $code,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        Request $request
    ): Response {
        $user = $userRepository->findOneBy(['confirmationCode' => $code]);

        if ($user === null) {
            return new Response('404');
        }

        $form = $this->createForm(UserPasswordResetFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->updatePassword($user, $form->getData(), $passwordHasher);

            $this->addFlash(
                'flash_password_reset',
                'Пароль изменён'
            );
        }

        return $this->render(
            'security/new_password.html.twig',
            [
                'errors' => $error ?? null,
                'passwordResetForm' => $form->createView()
            ]
        );
    }

    /**
     * Валидация поля ввода email
     * @param ValidatorInterface $validator
     * @param string $email
     * @return void
     * @throws Exception
     */
    private function emailValidation(ValidatorInterface $validator, string $email)
    {
        $violations = $validator->validate($email, [
            new NotBlank(['message' => 'Поле Email не может быть пустым']),
            new Email(['message' => 'Поле Email должно быть действительным email адресом'])
        ]);

        if ($violations->count() > 0) {
            throw new Exception($violations->get(0)->getMessage());
        }
    }
}
