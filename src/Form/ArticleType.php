<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $oldArticle = $this->articleRepository->findOneBy(['id' => $options['id']]);

        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'value' => $oldArticle ? $oldArticle->getTitle() : "",
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Article content',
                'data' => $oldArticle ? $oldArticle->getContent() : "",
            ])
            ->add('images', FileType::class, [
                'label' => "Article images",
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                ],
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '4096k',
                            'mimeTypes' => [
                                'image/png',
                                'image/jpg',
                                'image/jpeg',
                                'image/svg',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                        ]),
                    ]),
                ],
                'required' => false,
            ]);
        if ($options['id'] && sizeof($oldArticle->getImages()) > 0) {
            $builder->add('uploaded', ChoiceType::class, [
                'label' => 'Uploaded images',
                'multiple' => true,
                'expanded' => true,
                'choices' => $oldArticle->getImages(),
                'mapped' => false,
            ]);
        }
        $builder->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'multiple' => false,
            'expanded' => false,
            'data' => $oldArticle ? $oldArticle->getCategory() : null,
        ])
            ->add('submit', SubmitType::class, [
                'label' => $options['id'] ? 'Save article changes' : 'Create article',
                'attr' => [
                    'class' => 'form-btn',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
        $resolver->setRequired('id');
    }

    public function getBlockPrefix()
    {
        return 'article';
    }
}
