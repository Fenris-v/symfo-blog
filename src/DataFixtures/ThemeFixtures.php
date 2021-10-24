<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Persistence\ObjectManager;

class ThemeFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Theme::class, 10, function (Theme $theme) {
            $theme->setName($this->faker->words(3, true));
        });

        $manager->flush();
    }
}
