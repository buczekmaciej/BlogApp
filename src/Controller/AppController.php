<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticlesRepository;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;

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
    public function createArticle(SessionInterface $session, EntityManagerInterface $em)
    {
        $special=array('!','?','.',',','/','#','%','*','(',')','[',']','+','-','_','@','$','^','&','<','>','|',':',';','"',"'");
        $user=$session->get('user');
        $title='Most recent post! You have to check it out! 100% legit it is increadible';
        
        $slug=str_replace($special, "", $title);
        $slug=str_replace(' ','-', $slug);
        $slug=mb_strtolower($slug);

        $now=new \DateTime();

        $article=new Articles();
        $article->setTitle($title);
        $article->setContent('This is **most recent post** of *admin* that is about to check how app is working. **Let it go!**');
        $article->setCreatedAt($now);
        $article->setUser($user);
        $article->setLink($slug);

        $em->merge($article);
        $em->flush();


        return $this->render('name.html.twig', []);
    }
}
