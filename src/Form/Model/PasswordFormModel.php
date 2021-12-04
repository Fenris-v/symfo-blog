<?php

namespace App\Form\Model;

use App\Validator\PasswordCustom;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordFormModel
{
    /**
     * @PasswordCustom()
     * @Assert\Length(min="6", minMessage="Пароль должен быть длиной не меньше 6 символов")
     */
    public string $plainPassword;
}
