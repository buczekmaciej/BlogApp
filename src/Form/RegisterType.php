<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'register-username'
                ],
                'label' => 'Username'
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'register-password pass'
                ],
                'label' => 'Password'
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'register-email'
                ],
                'label' => 'E-mail'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'register-submit'
                ],
                'label' => 'Board in'
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
        return 'register';
    }
}
