<?php

namespace App\Form\Model;

use Symfony\Component\Form\AbstractType;

class ArticleCreateFormModel extends AbstractType
{
    public string $title;

    public string $keyword;

    public int $sizeFrom;

    public int $sizeTo;

    public array $images;
}
