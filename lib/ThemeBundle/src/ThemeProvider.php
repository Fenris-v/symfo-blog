<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle;

use Exception;

final class ThemeProvider
{
    public function __construct(private $theme)
    {
    }

    public function getThemesDto(): string
    {
        //                $themes = [];
        //                foreach (LiteratureThemeEnum::cases() as $case) {
        //                    $themes[$case->name] = $case->value;
        //                }
        //
        //                return new ThemesDto($themes);
        dd($this->theme);
        dd($this->theme->getTheme());
        return $this->theme->getTheme();
    }

    /**
     * @throws Exception
     */
    public function getParagraphs(string $theme, int $count): array
    {
        $data = $this->theme->getParagraphs();

        if (empty($data)) {
            throw new Exception('Не найдено ни одного параметра подходящей темы');
        }

        shuffle($data);
        return array_slice($data, 0, $count);
    }

    public function getTitle(string $theme): string
    {
        $data = $this->theme->getTitle();

        if (empty($data)) {
            return '';
        }

        shuffle($data);
        return $data[array_key_first($data)];
    }

    public function getImage(string $theme): string
    {
        $themes = $this->theme->getImages();
        shuffle($themes);

        return $themes[array_key_first($themes)];
    }

    public function getKeywords(string $theme): array
    {
        return $this->theme->getKeywords();
    }
}
