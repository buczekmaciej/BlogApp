<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'required' => TRUE,
                    'class' => 'art-inp'
                ],
                'label' => 'Article title'
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'required' => TRUE,
                    'class' => 'art-ta'
                ],
                'label' => 'Article text'
            ])
            ->add('image', FileType::class, [
                'attr' => [
                    'class' => 'art-img'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Select valid image(jpg, jpeg, png)'
                    ])
                ],
                'label' => 'Select image',
                'required' => FALSE
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'art-sub'
                ],
                'label' => 'Post article',
                'disabled' => TRUE
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function getBlockPrefix()
    {
        return 'article';
    }
}
