<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
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
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository, CommentRepository $commentRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRespository = $articleRepository;
        $this->commentRepository = $commentRepository;
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

                    return $this->redirectToRoute('articleView', ['id' => $article->getId()]);
                }
            } else {
                $error = "Such article already exists";
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

    /**
     * @Route("/{id}/edit", name="articleEdit")
     */
    public function edit(int $id, ?string $error = null, Request $request, ParameterBagInterface $parameterBag): Response
    {
        $form = $this->createForm(ArticleType::class, null, ['id' => $id]);
        $form->handleRequest($request);

        $oldArticle = $this->articleRespository->findOneBy(['id' => $id]);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $uploadedImages = $form->get('uploaded')->getData();
            $imgs = [];

            if ($article->getTitle() !== $oldArticle->getTitle() && $article->getContent() !== $oldArticle->getContent()) {
                if ($this->articleRespository->checkExistance($article->getTitle(), $article->getContent())) {
                    $error = "Such article already exists";
                }
            }

            $oldArticle->setPostedAt(new \DateTime);
            $oldArticle->setTitle($article->getTitle());
            $oldArticle->setContent($article->getContent());
            $oldArticle->setCategory($article->getCategory());

            if ($uploadedImages) {
                foreach ($uploadedImages as $img) {
                    $imgs[] = $img;
                }

                if (sizeof($oldArticle->getImages()) !== sizeof($imgs)) {
                    $diffs = array_diff($oldArticle->getImages(), $imgs);
                    $path = $parameterBag->get('kernel.project_dir') . '/public/uploads/';
                    $fileSystem = new Filesystem;

                    foreach ($diffs as $removed) {
                        $fileSystem->remove($path . $removed);
                    }
                }
            }

            if (sizeof($article->getImages()) > 0) {
                $highestIndex = (int) explode("_", explode(".", end($imgs))[0])[1];
                foreach ($article->getImages() as $ind => $img) {
                    try {
                        $name = $oldArticle->getId() . "_" . ($highestIndex + $ind + 1) . "." . $img->guessExtension();
                        $img->move(
                            "uploads/",
                            $name
                        );

                        $imgs[] = $name;
                    } catch (UploadException $e) {
                        throw new UploadException("Couldn't upload file", 500);
                    }
                }
            }

            $oldArticle->setImages($imgs);

            $this->entityManager->flush();

            return $this->redirectToRoute('articleView', ['id' => $id]);
        }

        return $this->render('article/edit.html.twig', [
            'location' => 'Edit article',
            'path' => 'Articles',
            'pathLink' => 'articleList',
            'form' => $form->createView(),
            'error' => $error,
            'oldCategory' => $oldArticle->getCategory(),
        ]);
    }

    /**
     * @Route("/{id}", name="articleView")
     */
    public function view(int $id): Response
    {
        $article = $this->articleRespository->findOneBy(['id' => $id]);

        return $this->render('article/view.html.twig', [
            'location' => strlen($article->getTitle()) > 30 ? substr($article->getTitle(), 0, 30) . "..." : $article->getTitle(),
            'path' => 'Articles',
            'pathLink' => 'articleList',
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/like", name="articleLike")
     */
    public function like(int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('articleView', ['id' => $id]);
        }

        $article = $this->articleRespository->findOneBy(['id' => $id]);
        $liked = false;

        foreach ($article->getLikes() as $likingUser) {
            if ($likingUser->getUsername() === $this->getUser()->getUsername()) {
                $liked = true;
                break;
            }
        }

        $liked ? $article->removeLike($this->getUser()) : $article->addLike($this->getUser());
        $this->entityManager->flush();

        return $this->redirectToRoute('articleView', ['id' => $id]);
    }

    /**
     * @Route("/{id}/comment/create", name="commentCreate")
     */
    public function commentCreate(int $id, Request $request): Response
    {
        try {
            $data = $request->request;
            $comment = new \App\Entity\Comment;
            $comment->setArticle($this->articleRespository->findOneBy(['id' => $id]));
            $comment->setAuthor($this->getUser());
            $comment->setPostedAt(new \DateTime);
            $comment->setContent($data->get('comment'));

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('articleView', ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Server wasn't able to add your comment. Please try again.", 500);
        }
    }

    /**
     * @Route("/comment/{id}/like", name="commentLike")
     */
    public function commentLike(int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('articleView', ['id' => $id]);
        }

        $comment = $this->commentRepository->findOneBy(['id' => $id]);
        $liked = false;

        foreach ($comment->getLikes() as $likingUser) {
            if ($likingUser->getUsername() === $this->getUser()->getUsername()) {
                $liked = true;
                break;
            }
        }

        $liked ? $comment->removeLike($this->getUser()) : $comment->addLike($this->getUser());
        $this->entityManager->flush();

        return $this->redirectToRoute('articleView', ['id' => $comment->getArticle()->getId()]);
    }
}
