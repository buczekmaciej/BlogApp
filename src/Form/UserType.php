<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->userRepository->findOneBy(['id' => $options['id']]);

        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'disabled' => true,
                ],
                'label' => 'Username',
                'required' => false,
                'data' => $user->getUsername(),
                'empty_data' => $user->getUsername(),
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => false,
                'help' => 'Leaving it blank will keep old password',
                'empty_data' => "",
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'data' => $user->getEmail(),
            ])
            ->add("submit", SubmitType::class, [
                'label' => 'Update profile',
                'attr' => [
                    'class' => 'form-btn',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
        $resolver->setRequired("id");
    }

    public function getBlockPrefix()
    {
        return 'userUpdate';
    }
}
