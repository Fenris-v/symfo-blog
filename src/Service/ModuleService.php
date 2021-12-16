<?php

declare(strict_types=1);

namespace App\Service;

use App\Contracts\ModuleLengthContract;
use App\Dto\ModuleDto;

class ModuleService implements ModuleLengthContract
{
    public function __construct(private array $modules)
    {
    }

    public function getLength(): int
    {
        $length = 0;
        foreach ($this->modules as $module) {
            $length += $module->getLength();
        }

        return $length;
    }

    public function setParagraphs(array $paragraphs): void
    {
        /** @var ModuleDto $module */
        foreach ($this->modules as $module) {
            $moduleParagraphs = [];
            for ($i = 0; $i < $module->getLength(); $i++) {
                $moduleParagraphs[] = array_shift($paragraphs);
            }

            $module->setParagraphs($moduleParagraphs);
        }
    }

    public function getModules(): array
    {
        return $this->modules;
    }
}
