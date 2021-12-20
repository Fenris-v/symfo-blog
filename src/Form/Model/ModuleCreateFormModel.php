<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ModuleCreateFormModel extends PasswordFormModel
{
    /**
     * @Assert\NotBlank(message="Поле не может быть пустым")
     */
    public string $name;

    /**
     * @Assert\NotBlank(message="Поле не может быть пустым")
     */
    public string $code;
}
