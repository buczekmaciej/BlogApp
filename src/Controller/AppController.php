<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticlesRepository;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="appHomepage")
     */
    public function homepage(SessionInterface $session, ArticlesRepository $aR)
    {
        $logged=$session->get('user');
        if(!$logged)
        {
            return $this->redirectToRoute('userLogin', []);
        }
        $id=$logged->getId();
        $posts=$aR->findBy(['user'=>$id]);

        return $this->render('app/index.html.twig', [
            'name'=>$logged->getLogin(),
            'posts'=>$posts
        ]);
    }

    /**
     * @Route("/article/new", name="newArticle")
     */
    public function createArticle(SessionInterface $session, EntityManagerInterface $em, Request $req)
    {
        $special=array('!','?','.',',','/','#','%','*','(',')','[',']','+','-','_','@','$','^','&','<','>','|',':',';','"',"'");
        $user=$session->get('user');
        
        $form=$this->createFormBuilder()
        ->add('Title', TextType::class, [
            'attr'=>[
                'class'=>'ninp',
                'placeholder'=>'Title'
            ]
        ])
        ->add('Content', TextareaType::class, [
            'attr'=>[
                'class'=>'narea',
                'placeholder'=>'Post content'
            ]
        ])
        ->add('Create', SubmitType::class, [
            'class'=>'nsub'
        ])
        ->getForm();
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $slug=str_replace($special, "", $title);
            $slug=str_replace(' ','-', $slug);
            $slug=mb_strtolower($slug);

            $now=new \DateTime();

            $article=new Articles();
            $article->setTitle($title);
            $article->setContent();
            $article->setCreatedAt($now);
            $article->setUser($user);
            $article->setLink($slug);

            $em->merge($article);
            $em->flush();

            return $this->redirectToRoute('appHomepage', []);
        }

        return $this->render('app/new.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/article/{slug}", name="articleShow")
     */
    public function articleShow($slug)
    {

        return new Response();
    }
}
