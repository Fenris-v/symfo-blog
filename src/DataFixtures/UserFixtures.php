<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    function loadData(ObjectManager $manager)
    {
        $this->create(User::class, function (User $user) use ($manager) {
            $user->setEmail('admin@symfony.skillbox')
                ->setFirstName('Admin')
                ->setPassword(
                    $this->passwordHasher
                        ->hashPassword($user, '123456')
                )->setIsActive(true)
                ->setSubscription($this->getRandomReference(Subscription::class))
            ->setSubscriptionLeft($this->faker->dateTimeBetween('+1days', '+3days'));
        });

        $this->createMany(User::class, 10, function (User $user) {
            $user->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName)
                ->setPassword(
                    $this->passwordHasher
                        ->hashPassword($user, '123456')
                )->setIsActive(true)
                ->setSubscription($this->getRandomReference(Subscription::class))
                ->setSubscriptionLeft($this->faker->dateTimeBetween('-2days','+7days'));
        });

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            Subscription::class
        ];
    }
}
