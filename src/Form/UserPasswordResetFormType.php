<?php

namespace App\Form;

use App\Form\Model\PasswordFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordResetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PasswordFormModel::class,
            ]
        );
    }
}
