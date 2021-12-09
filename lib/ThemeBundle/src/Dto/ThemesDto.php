<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Dto;

class ThemesDto
{
    public function __construct(private array $themes)
    {
    }

    public function getThemes(): array
    {
        return $this->themes;
    }
}
