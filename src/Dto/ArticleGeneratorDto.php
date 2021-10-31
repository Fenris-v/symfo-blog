<?php

namespace App\Dto;

class ArticleGeneratorDto extends AbstractDto
{
    public string $theme;
    public string $title;
    public array $keyword;
    public int $sizeFrom;
    public int $sizeTo;
    public array $wordField;
    public array $wordCountField;
}
