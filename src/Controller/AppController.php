<?php

namespace App\Controller;

use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;

class AppController extends AbstractController
{
    public function __construct(ArticlesRepository $ar)
    {
        $this->ar = $ar;
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

        $comment = $this->createForm(CommentType::class);
        $comment->handleRequest($request);

        if ($comment->isSubmitted() && $comment->isValid()) {
        }

        return $this->render('app/show.html.twig', [
            'comment' => $comment->createView(),
            'article' => $article
        ]);
    }

    /**
     * @Route("/404", name="404error")
     */
    public function error404()
    {
        return $this->render('404.html.twig', []);
    }
}
