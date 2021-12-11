<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Contracts\Themes;

interface ThemesContract
{
    public function getTheme(): string;

    public function getParagraphs(): array;

    public function getTitles(): array;

    public function getKeywords(): array;

    public function getImages(): array;
}
