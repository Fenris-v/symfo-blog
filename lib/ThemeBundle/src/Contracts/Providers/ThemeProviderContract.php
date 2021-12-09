<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Contracts\Providers;

use Fenris\ThemeBundle\Dto\ThemesDto;

interface ThemeProviderContract
{
    public function getThemesDto(): ThemesDto;

    public function getParagraphs(string $theme, int $count): array;

    public function getTitle(string $theme): string;

    public function getImage(string $theme): string;

    public function getKeywords(string $theme): array;
}
