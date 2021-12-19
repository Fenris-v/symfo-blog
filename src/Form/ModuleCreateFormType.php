<?php

namespace App\Form;

use App\Form\Model\ModuleCreateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', null, [
            'attr' => [
                'autofocus' => 'autofocus',
                'placeholder' => 'Module name'
            ],
            'label' => 'Module name'
        ])->add('code', TextareaType::class, [
            'label' => 'Module code'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModuleCreateFormModel::class
        ]);
    }
}
