<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordFormModel
{
    /**
     * @Assert\NotBlank(message="Пароль не указан")
     * @Assert\Length(min="6", minMessage="Пароль должен быть длиной не меньше 6 символов")
     */
    public string $plainPassword;
}
