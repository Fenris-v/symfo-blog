<?php

namespace App\Repository;

use App\Dto\ProfileDto;
use App\Entity\User;
use App\Form\Model\PasswordFormModel;
use App\Form\Model\UserRegistrationFormModel;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Создает пользователя
     * @param UserRegistrationFormModel $formModel
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createUser(UserRegistrationFormModel $formModel): User
    {
        $user = new User();

        $user->setFirstName($formModel->firstName)
            ->setEmail($formModel->email)
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $formModel->plainPassword
                )
            );

        $this->updateConfirmationCode($user);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * Подтверждение регистрации
     * @param string $code
     * @return User|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function confirm(string $code): ?User
    {
        $user = $this->findOneBy(['confirmationCode' => $code]);

        if ($user === null) {
            return null;
        }

        $user->setIsActive(true);
        $user->setConfirmationCode(null);

        $this->_em->flush();

        return $user;
    }

    /**
     * Обновляет код подтверждения
     * @param User $user
     * @param bool $save
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateConfirmationCode(User $user, bool $save = false)
    {
        $user->setConfirmationCode(md5(uniqid(rand(), true)));

        if ($save) {
            $this->_em->persist($user);
            $this->_em->flush();
        }
    }

    /**
     * Обновляет пароль
     * @param User $user
     * @param PasswordFormModel $formModel
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updatePassword(User $user, PasswordFormModel $formModel): User
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $formModel->plainPassword
            )
        )->setConfirmationCode(null);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * @param User $user
     * @param string $subscriptionSlug
     * @param DateTime $dateOfEnd
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateSubscriptionLevel(
        User $user,
        string $subscriptionSlug,
        DateTime $dateOfEnd
    ): User {
        $user->setSubscription($subscriptionSlug)->setSubscriptionLeft($dateOfEnd);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function updateUser(User $user, ProfileDto $profileDto): User
    {
        $user->setEmail($profileDto->getEmail());
        $user->setFirstName($profileDto->getName());

        if ($profileDto->getPassword() !== null) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $profileDto->getPassword()
                )
            );
        }

        $this->_em->flush();
        return $user;
    }
}
