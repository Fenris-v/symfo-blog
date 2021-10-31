<?php

namespace App\DataFixtures;

use App\Entity\TextTemplate;
use App\Repository\ThemeRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TextTemplateFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function __construct(private ThemeRepository $themeRepository)
    {
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->themeRepository->findAll() as $theme) {
            $this->createMany(
                TextTemplate::class,
                $this->faker->numberBetween(10, 15),
                function (TextTemplate $textTemplate) use ($theme) {
                    $paragraph = $this->faker
                        ->sentences($this->faker->numberBetween(3, 10), true);
                    $paragraph = $this->setPlaceholders(
                        $paragraph,
                        $this->faker->numberBetween(1, 3)
                    );

                    $textTemplate->setTheme($theme)->setTemplate($paragraph);
                }
            );
        }

        $manager->flush();
    }

    /**
     * Устанавливает порядок фикстур в зависимости от указанных в массиве
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            ThemeFixtures::class
        ];
    }

    /**
     * @param string $className
     * @param int $count
     * @param callable $factory
     */
    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->create($className, $factory);
        }
    }


    /**
     * @param string $paragraph
     * @param int $placeholdersCount
     * @return string
     */
    private function setPlaceholders(string $paragraph, int $placeholdersCount): string
    {
        $text = explode(' ', $paragraph);

        for ($i = 0; $i < $placeholdersCount; $i++) {
            array_splice(
                $text,
                $this->faker->numberBetween(1, count($text) - 1),
                0,
                ["{{keyword|morph({$this->faker->numberBetween(0, 6)})}}"]
            );
        }

        return implode(' ', $text);
    }
}

