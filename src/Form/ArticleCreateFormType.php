<?php

namespace App\Form;

use App\Form\Model\ArticleCreateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', null, [
                'attr' => ['placeholder' => 'Keyword'],
            ])
            ->add('title', null, [
                'attr' => ['placeholder' => 'Title'],
            ])
            ->add('sizeFrom', null, [
                'attr' => ['placeholder' => 'Size from']
            ])
            ->add('sizeTo', null, [
                'attr' => ['placeholder' => 'Size to']
            ])
            ->add('images', FileType::class, [
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ArticleCreateFormModel::class,
            ]
        );
    }
}
