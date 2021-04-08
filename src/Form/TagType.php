<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $name = $options['tagName'] ?? "";

        $builder
            ->add('name', TextType::class, [
                'label' => 'Tag name',
                'attr' => [
                    'value' => $name,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $name == "" ? 'Submit new tag' : "Save tag changes",
                'attr' => [
                    'class' => 'form-btn',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
        $resolver->setRequired("tagName");
    }

    public function getBlockPrefix()
    {
        return 'newTag';
    }
}
