<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle;

use Fenris\ThemeBundle\Contracts\Providers\ThemeProviderContract;
use Fenris\ThemeBundle\Dto\ThemesDto;

final class ThemeProvider
{
    public function __construct(private ThemeProviderContract $themeProvider)
    {
    }

    public function getThemesDto(): ThemesDto
    {
        return $this->themeProvider->getThemesDto();
    }

    public function getParagraphs(string $themes, int $count): array
    {
        return $this->themeProvider->getParagraphs($themes, $count);
    }

    public function getTitle(string $theme): string
    {
        return $this->themeProvider->getTitle($theme);
    }

    public function getKeywords(string $theme): array
    {
        return $this->themeProvider->getKeywords($theme);
    }

    public function getImage(string $theme): string
    {
        return $this->themeProvider->getImage($theme);
    }
}
