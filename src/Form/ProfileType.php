<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'profile-pass'
                ],
                'required' => FALSE,
                'label' => 'Password'
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'profile-mail'
                ],
                'required' => FALSE,
                'label' => 'E-mail',
                'data' => $user->getEmail()
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'profile-name'
                ],
                'required' => FALSE,
                'label' => 'First name',
                'data' => $user->getDetails()->getFirstName()
            ])
            ->add('location', TextType::class, [
                'attr' => [
                    'class' => 'profile-loc'
                ],
                'required' => FALSE,
                'label' => 'Location',
                'data' => $user->getDetails()->getLocation()
            ])
            ->add('bio', TextareaType::class, [
                'attr' => [
                    'class' => 'profile-bio'
                ],
                'required' => FALSE,
                'label' => 'Bio',
                'data' => $user->getDetails()->getBio()
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
                    'class' => 'profile-sub'
                ],
                'label' => 'Update profile'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setAllowedTypes('user', [\App\Entity\User::class, 'object']);
        $resolver->setDefaults([]);
    }

    public function getBlockPrefix()
    {
        return 'profile';
    }
}
