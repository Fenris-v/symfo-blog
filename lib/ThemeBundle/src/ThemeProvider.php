<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle;

use Exception;
use Fenris\ThemeBundle\Contracts\Themes\ThemesContract;

final class ThemeProvider
{
    private ?ThemesContract $theme = null;

    public function __construct(private array $themes)
    {
    }

    public function setTheme(ThemesContract $theme): void
    {
        $this->theme = $theme;
    }

    public function setRandomTheme(): void
    {
        $themes = $this->themes;
        shuffle($themes);
        $this->theme = new $themes[array_key_first($themes)];
    }

    public function getThemes(): array
    {
        $themes = [];
        /** @var ThemesContract $theme */
        foreach ($this->themes as $theme) {
            $themes[$theme::class] = $theme->getTheme();
        }

        return $themes;
    }

    /**
     * @throws Exception
     */
    public function getParagraphs(int $count): array
    {
        if ($this->theme === null) {
            $this->setRandomTheme();
        }

        $data = $this->theme->getParagraphs();

        if (empty($data)) {
            throw new Exception('Не найдено ни одного параметра подходящей темы');
        }

        shuffle($data);
        return array_slice($data, 0, $count);
    }

    public function getTitle(): string
    {
        $titles = $this->theme->getTitles();
        shuffle($titles);

        return $titles[array_key_first($titles)];
    }

    public function getImage(): string
    {
        $themes = $this->theme->getImages();
        shuffle($themes);

        return $themes[array_key_first($themes)];
    }

    public function getKeywords(): array
    {
        return $this->theme->getKeywords();
    }
}
