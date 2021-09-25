<?php

namespace App\Form;

use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'attr' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => 'Your name',
                ],
                'label' => 'Your name',
            ])
            ->add('email', null, [
                'attr' => ['placeholder' => 'Your Email'],
                'label' => 'Your Email',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли должны совпадать',
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['placeholder' => 'Password']
                ],
                'second_options' => [
                    'label' => 'Confirm password',
                    'attr' => ['placeholder' => 'Confirm password']
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['class' => 'btn btn-lg btn-primary btn-block text-uppercase']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserRegistrationFormModel::class,
            ]
        );
    }
}
