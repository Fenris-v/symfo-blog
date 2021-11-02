<?php

namespace App\Form;

use App\Form\Model\ArticleCreateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, [
                'attr' => ['placeholder' => 'Keyword'],
            ])
            ->add('title', null, [
                'attr' => ['placeholder' => 'Title'],
            ])
            ->add('sizeFrom', IntegerType::class, [
                'attr' => ['placeholder' => 'Size from', 'required' => false],
                'required' => false
            ])
            ->add('sizeTo', IntegerType::class, [
                'attr' => ['placeholder' => 'Size to', 'required' => false],
                'required' => false
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create',
                'attr' => [
                    'class' => 'btn btn-lg btn-primary btn-block text-uppercase',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ArticleCreateFormModel::class,
                'allow_extra_fields' => true
            ]
        );
    }
}
