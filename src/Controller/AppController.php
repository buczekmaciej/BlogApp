<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticlesRepository;
use App\Entity\Articles;
use App\Entity\Comments;
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
        $posts=$aR->findBy(array(), array('createdAt'=>'DESC'));
        dump($posts);
        $recent=$aR->findOneBy(array(),array('createdAt'=>'DESC'), 1);

        return $this->render('app/index.html.twig', [
            'name'=>$logged->getLogin(),
            'recent'=>$recent,
            'posts'=>$posts
        ]);
    }

    /**
     * @Route("/article/new", name="newArticle")
     */
    public function createArticle(SessionInterface $session, EntityManagerInterface $em, Request $req)
    {    
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
            'attr'=>[
                'class'=>'nsub'
            ]
        ])
        ->getForm();
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $data=$form->getData();

            $user=$session->get('user');

            $title=$data['Title'];
            $content=$data['Content'];
            
            $special=array('!','?','.',',','/','#','%','*','(',')','[',']','+','-','_','@','$','^','&','<','>','|',':',';','"',"'");

            $slug=str_replace($special, "", $title);
            $slug=str_replace(' ','-', $slug);
            $slug=mb_strtolower($slug);

            $exist=$this->getDoctrine()->getRepository(Articles::class)->findBy(['link'=>$slug]);
            if($exist)
            {
                $this->addFlash(
                    'danger',
                    'Sorry there is post titled like that'
                );
            }
            else
            {
                $now=new \DateTime();

                $article=new Articles();
                $article->setTitle($title);
                $article->setContent($content);
                $article->setCreatedAt($now);
                $article->setUser($user);
                $article->setLink($slug);

                $em->merge($article);
                $em->flush();

                return $this->redirectToRoute('articleShow', [
                    'slug'=>$slug
                ]);
            }
        }

        return $this->render('app/new.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/article/{slug}", name="articleShow")
     */
    public function articleShow($slug,EntityManagerInterface $em, SessionInterface $session, ArticlesRepository $aR, Request $req)
    {
        $user=$session->get('user');

        $post=$aR->findBy(['link'=>$slug]);
        $id=$post[0]->getId();
        $post=$post[0];
        $comments=$this->getDoctrine()->getRepository(Comments::class)->findBy(array('Article'=>$id), array('addedAt'=>'DESC'));
        
        $form=$this->createFormBuilder()
        ->add('Comment', TextType::class, [
            'attr'=>[
                'class'=>'cinp',
                'placeholder'=>'Comment content'
            ]
        ])
        ->add('Submit', SubmitType::class, [
            'attr'=>[
                'class'=>'csub'
            ]
        ])
        ->getForm();

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $content=$form->getData();
            
            $now=new \DateTime();

            $comment=new Comments();
            $comment->setContent($content['Comment']);
            $comment->setAddedAt($now);
            $comment->setUser($user);
            $comment->setArticle($post);

            $em->merge($comment);
            $em->flush();

            return $this->redirectToRoute('articleShow', ['slug'=>$slug]);
        }

        return $this->render('app/show.html.twig', [
            'name'=>$user->getLogin(),
            'post'=>$post,
            'comments'=>$comments,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/search/{value}", name="articleSearch", methods={"GET","POST"})
     */
    public function search($value, SessionInterface $session)
    {
        $user=$session->get('user');

        $result=$this->getDoctrine()->getRepository(Articles::class)->checkIfContain($value);

        return $this->render('app/search.html.twig', [
            'name'=>$user->getLogin(),
            'result'=>$result
        ]);;
    }
}
