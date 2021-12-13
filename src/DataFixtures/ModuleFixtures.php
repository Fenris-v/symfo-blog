<?php

namespace App\DataFixtures;

use App\Entity\Module;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ModuleFixtures extends BaseFixtures implements FixtureGroupInterface
{
    function loadData(ObjectManager $manager)
    {
        $dir = __DIR__ . '/modules/';
        foreach (scandir($dir) as $item) {
            if (is_dir($dir . $item)) {
                continue;
            }

            $template = file_get_contents($dir . $item);
            $this->create(
                Module::class,
                function (Module $module) use ($template) {
                    $module->setTemplate($template)
                        ->setName(
                            $this->faker->words(
                                $this->faker->numberBetween(1, 3),
                                true
                            )
                        );
                }
            );
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['modules'];
    }
}
