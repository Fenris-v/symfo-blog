<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Contracts\Enums;

interface ThemesContract
{
    public function getParagraphs(): array;

    public function getTitle(): array;

    public function getImage(): string;
}
