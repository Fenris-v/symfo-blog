<?php

namespace App\Form\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleCreateFormModel extends AbstractType
{
    public string $title;

    /**
     * @Assert\NotBlank(message="Поле не может быть пустым")
     */
    public string $keyword;

    public int $sizeFrom;

    public int $sizeTo;

    public array $images;
}
