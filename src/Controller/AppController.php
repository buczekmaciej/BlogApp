<?php

namespace App\Controller;

use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class AppController extends AbstractController
{
    public function __construct(ArticlesRepository $ar, EntityManagerInterface $em)
    {
        $this->ar = $ar;
        $this->em = $em;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $posts = $this->ar->findBy(array(), array('createdAt' => 'DESC'), 5);
        $mostLiked = $this->ar->mostLiked();

        return $this->render('app/homepage.html.twig', [
            'posts' => $posts,
            'liked' => $mostLiked
        ]);
    }

    /**
     * @Route("/articles", name="articlesPagination")
     */
    public function articlesPagination(PaginatorInterface $paginator, Request $request)
    {
        return $this->render('app/pagination.html.twig', [
            'pagination' => $paginator->paginate($this->ar->findAll(), $request->query->getInt('page', 1), 10)
        ]);
    }

    /**
     * @Route("/article/{link}", name="displayArticle")
     */
    public function displayArticle(string $link, Request $request)
    {
        $article = $this->ar->findOneBy(['link' => $link]);

        if (!$article) return $this->redirectToRoute('404error', []);

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = new \App\Entity\Comments();
            $comment->setContent($form->getData()['comment']);
            $comment->setAddedAt(new \DateTime());
            $comment->setArticle($article);
            $comment->setUser($this->getUser());

            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('displayArticle', ['link' => $link]);
        }

        return $this->render('app/show.html.twig', [
            'comment' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * @Route("/article/{link}/like", name="likeArticle")
     */
    public function likeArticle(string $link)
    {
        $article = $this->ar->findOneBy(['link' => $link]);

        if (!$article) return $this->redirectToRoute('404error', []);

        $liked = false;

        foreach ($article->getLikes() as $like) {
            if ($this->getUser()->getId() == $like->getId()) {
                $liked = true;
                break;
            }
        }

        $liked ? $article->removeLike($this->getUser()) : $article->addLike($this->getUser());
        $this->em->flush();

        return $this->redirectToRoute('displayArticle', ['link' => $link]);
    }

    /**
     * @Route("/404", name="404error")
     */
    public function error404()
    {
        return $this->render('404.html.twig', []);
    }
}
