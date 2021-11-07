<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Entity\User;
use App\Enums\Subscription as SubscriptionEnum;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    function loadData(ObjectManager $manager)
    {
        $levels = [
            SubscriptionEnum::Free->value,
            SubscriptionEnum::Plus->value,
            SubscriptionEnum::Pro->value
        ];

        $this->create(User::class, function (User $user) use ($manager, $levels) {
            $user->setEmail('admin@symfony.skillbox')
                ->setFirstName('Admin')
                ->setPassword(
                    $this->passwordHasher
                        ->hashPassword($user, '123456')
                )->setIsActive(true)
                ->setSubscription($this->faker->randomElement($levels))
                ->setSubscriptionLeft($this->faker->dateTimeBetween('+1days', '+3days'));
        });

        $this->createMany(User::class, 10, function (User $user) use ($levels) {
            $user->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName)
                ->setPassword(
                    $this->passwordHasher
                        ->hashPassword($user, '123456')
                )->setIsActive(true)
                ->setSubscription($this->faker->randomElement($levels))
                ->setSubscriptionLeft($this->faker->dateTimeBetween('-2days', '+7days'));
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
