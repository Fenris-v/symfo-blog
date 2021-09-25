<?php

namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel extends PasswordFormModel
{
    /**
     * @Assert\NotBlank(message="Поле не может быть пустым")
     * @Assert\Email(message="Поле должно быть действительным email адресом")
     * @UniqueUser()
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="Поле не может быть пустым")
     */
    public string $firstName;
}
