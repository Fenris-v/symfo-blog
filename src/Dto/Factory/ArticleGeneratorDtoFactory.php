<?php

namespace App\Dto\Factory;

use App\Dto\ArticleGeneratorDto;

class ArticleGeneratorDtoFactory
{
    /**
     * @param array $data
     * @return ArticleGeneratorDto
     */
    public static function createFromArray(array $data): ArticleGeneratorDto
    {
        $dto = new ArticleGeneratorDto();

        foreach (get_class_vars(ArticleGeneratorDto::class) as $key => $prop) {
            if ($key === 'keyword') {
                self::setKeyword($dto, $data);
                continue;
            }

            $dto->$key = $data[$key] ?? null;
        }

        $dto->title = $data['title'] ?? '';

        return $dto;
    }

    /**
     * @param ArticleGeneratorDto $dto
     * @param array $data
     */
    private static function setKeyword(ArticleGeneratorDto $dto, array $data)
    {
        $dto->keyword[0] = $data['keyword'] ?? null;

        if (!empty($dto->keyword) && isset($data['declination'])) {
            foreach ($data['declination'] as $key => $declination) {
                $dto->keyword[$key] = $declination;
            }
        }
    }
}
