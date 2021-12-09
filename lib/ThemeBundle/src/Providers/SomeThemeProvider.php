<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Providers;

use Exception;
use Fenris\ThemeBundle\Contracts\Providers\ThemeProviderContract;
use Fenris\ThemeBundle\Dto\ThemesDto;
use Fenris\ThemeBundle\Enums\ThemesEnum;

class SomeThemeProvider implements ThemeProviderContract
{
    public function getThemesDto(): ThemesDto
    {
        $themes = [];
        foreach (ThemesEnum::cases() as $case) {
            $themes[$case->name] = $case->value;
        }

        return new ThemesDto($themes);
    }

    /**
     * @throws Exception
     */
    public function getParagraphs(string $theme, int $count): array
    {
        $data = match ($theme) {
            'Programming' => ThemesEnum::Programming->getParagraphs(),
            'Cooking' => ThemesEnum::Cooking->getParagraphs(),
            'Literature' => ThemesEnum::Literature->getParagraphs(),
            default => []
        };

        if (empty($data)) {
            throw new Exception('Не найдено ни одного параметра подходящей темы');
        }

        shuffle($data);
        return array_slice($data, 0, $count);
    }

    public function getTitle(string $theme): string
    {
        $data = match ($theme) {
            'Programming' => ThemesEnum::Programming->getTitle(),
            'Cooking' => ThemesEnum::Cooking->getTitle(),
            'Literature' => ThemesEnum::Literature->getTitle(),
            default => []
        };

        if (empty($data)) {
            return '';
        }

        shuffle($data);
        return $data[array_key_first($data)];
    }

    public function getImage(string $theme): string
    {
        return match ($theme) {
            'Programming' => ThemesEnum::Programming->getImage(),
            'Cooking' => ThemesEnum::Cooking->getImage(),
            'Literature' => ThemesEnum::Literature->getImage(),
            default => ''
        };
    }

    public function getKeywords(string $theme): array
    {
        return match ($theme) {
            'Programming' => ThemesEnum::Programming->getKeywords(),
            'Cooking' => ThemesEnum::Cooking->getKeywords(),
            'Literature' => ThemesEnum::Literature->getKeywords(),
            default => []
        };
    }
}
