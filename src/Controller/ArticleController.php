<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 */
class ArticleController extends AbstractController
{
    private $entityManager;
    private $articleRespository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRespository = $articleRepository;
    }

    /**
     * @Route("/", name="articleList")
     */
    function list(PaginatorInterface $paginator, Request $request): Response {
        return $this->render('article/list.html.twig', [
            'location' => 'List of articles',
            'path' => 'Articles',
            'pathLink' => 'articleList',
            'articles' => $paginator->paginate($this->articleRespository->findBy([], ['postedAt' => 'DESC']), $request->query->getInt('page', 1), 20)
        ]);
    }

    /**
     * @Route("/create", name="articleCreate")
     */
    public function create(?string $error = null, Request $request): Response
    {
        $form = $this->createForm(ArticleType::class, null, ['id' => null]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $article->setAuthor($this->getUser());
            $article->setPostedAt(new \DateTime);
            $files = $article->getImages();
            $article->setImages([]);

            if (!$this->articleRespository->checkExistance($article->getTitle(), $article->getContent())) {
                $this->entityManager->persist($article);
                $this->entityManager->flush();

                if (sizeof($files) > 0) {
                    $temp = [];

                    foreach ($files as $ind => $file) {
                        try {
                            $name = $article->getId() . "_" . $ind . "." . $file->guessExtension();
                            $file->move(
                                "uploads/",
                                $name
                            );
                            $temp[] = $name;
                        } catch (UploadException $e) {
                            throw new UploadException("Couldn't upload file", 500);
                        }
                    }

                    $article->setImages($temp);
                    unset($temp);
                    unset($files);

                    $this->entityManager->flush();

                    return $this->redirectToRoute('articleList');
                }
            }
        }

        return $this->render('article/create.html.twig', [
            'location' => 'Create article',
            'path' => 'Articles',
            'pathLink' => 'articleList',
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
