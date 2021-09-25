<?php

namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
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

    /**
     * @Assert\NotBlank(message="Пароль не указан")
     * @Assert\Length(min="6", minMessage="Пароль должен быть длиной не меньше 6 символов")
     */
    public string $plainPassword;
}
