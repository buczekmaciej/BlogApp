<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticlesRepository;
use App\Repository\UserRepository;
use App\Repository\CommentsRepository;
use App\Entity\Articles;
use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class AppController extends AbstractController
{
    /**
     * @Route("/", name="appHomepage")
     */
    public function homepage(ArticlesRepository $aR)
    {
        $posts=$aR->findBy(array(), array('createdAt'=>'DESC'), 5);

        return $this->render('app/index.html.twig', [
            'posts'=>$posts
        ]);
    }

    /**
     * @Route("/article/new", name="newArticle")
     */
    public function createArticle(SessionInterface $session, EntityManagerInterface $em, Request $req)
    {
        $logged = $session->get('user');
        if(!$logged)
        {
            return $this->redirectToRoute('userLogin', []);
        }

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
     * @Route("/article/{slug}/like", name="likeArticle")
     */
    public function likeArticle($slug, ArticlesRepository $aR, UserRepository $uR, EntityManagerInterface $em, SessionInterface $session)
    {
        $logged = $session->get('user');
        if ($logged) {
            $article = $aR->findBy(['link'=>$slug]);
            if ($article) {
                $login = $session->get('user')->getLogin();
                if($login){
                    $article = $article[0];

                    $liked = false;
                    $user = $uR->findBy(['Login'=>$login])[0];

                    foreach($article->getLikes() as $like)
                    {
                        if($user === $like)
                        {
                            $liked = true;
                        }
                    }

                    if ($liked == false) {
                        $article->addLike($user);
                    } else {
                        $article->removeLike($user);
                    }

                    $em->flush();
                    return $this->redirectToRoute('articleShow', ['slug'=>$slug]);
                }
                else{
                    return $this->redirectToRoute('userLogin', []);
                }
            } else {
                return $this->redirectToRoute('appHomepage', []);
            }
        } else {
           return $this->redirectToRoute('appHomepage', []);
        }
        
    }

    /**
     * @Route("/article/{slug}", name="articleShow")
     */
    public function articleShow($slug,EntityManagerInterface $em, UserRepository $uR, SessionInterface $session, ArticlesRepository $aR, Request $req, CommentsRepository $cR)
    {
        $post=$aR->findBy(['link'=>$slug]);

        if ($post) {
            $user=$session->get('user');

            $liked = false;
            if($user){
                $user = $uR->findBy(['Login'=>$user->getLogin()])[0];
                foreach($post[0]->getLikes() as $like)
                {
                    if($user === $like)
                    {
                        $liked = true;
                    }
                }
            }

            $id=$post[0]->getId();
            $comments=$cR->findBy(array('Article'=>$id),array('addedAt'=>'DESC'));
            

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
                ],
                'label'=>'Add comment'
            ])
            ->getForm();

            $form->handleRequest($req);

            if($form->isSubmitted() && $form->isValid())
            {
                $content=$form->getData();

                $comment=new Comments();
                $comment->setContent($content['Comment']);
                $comment->setAddedAt(new \DateTime());
                $comment->setUser($user);
                $comment->setArticle($post[0]);

                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('articleShow', ['slug'=>$slug]);
            }

            return $this->render('app/show.html.twig', [
                'post'=>$post[0],
                'comments'=>$comments,
                'form'=>$form->createView(),
                'liked'=>$liked
            ]);
        } 
        else {
            return $this->render('app/show.html.twig', [
                'post'=>$post
            ]);
        }
        
    }

    /**
     * @Route("/search/{value}", name="articleSearch", methods={"GET","POST"})
     */
    public function search($value, SessionInterface $session)
    {
        $user=$session->get('user');

        $result=$this->getDoctrine()->getRepository(Articles::class)->checkIfContain($value);

        return $this->render('app/search.html.twig', [
            'result'=>$result,
            'query'=>$value
        ]);;
    }
}
