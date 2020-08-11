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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="appHomepage")
     */
    public function homepage(ArticlesRepository $aR, CommentsRepository $cR)
    {
        $posts = $aR->findBy(array(), array('createdAt' => 'DESC'), 5);
        $recentComments = $cR->findBy(array(), array('addedAt' => 'DESC'), 5);
        $mostLiked = $aR->mostLiked();

        foreach ($posts as $post) {
            if ($post->getImage()) {
                $post->setImage(stream_get_contents($post->getImage()));
            }
        }

        return $this->render('app/index.html.twig', [
            'posts' => $posts,
            'comms' => $recentComments,
            'liked' => $mostLiked
        ]);
    }

    /**
     * @Route("/articles", name="articlesPagination")
     */
    public function articlesPagination(PaginatorInterface $paginator, Request $request, ArticlesRepository $aR)
    {
        $articles = $aR->findAll();
        return $this->render('app/pagination.html.twig', [
            'pagination' => $paginator->paginate($articles, $request->query->getInt('page', 1), 10)
        ]);
    }

    /**
     * @Route("/article/new", name="newArticle")
     */
    public function createArticle(SessionInterface $session, EntityManagerInterface $em, Request $req, ArticlesRepository $aR, UserRepository $uR)
    {
        $logged = $session->get('user');
        if (!$logged) {
            return $this->redirectToRoute('userLogin', []);
        }

        $form = $this->createFormBuilder()
            ->add('Title', TextareaType::class, [
                'attr' => [
                    'class' => 'ninp',
                ]
            ])
            ->add('Content', TextareaType::class, [
                'attr' => [
                    'class' => 'narea',
                ]
            ])
            ->add('IMG', FileType::class, [
                'label' => 'Article image (JPG, JPEG, PNG)',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Select valid image(jpg, jpeg, png)'
                    ])
                ]
            ])
            ->add('Create', SubmitType::class, [
                'attr' => [
                    'class' => 'nsub'
                ]
            ])
            ->getForm();

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $session->get('user');

            $special = array('!', '?', '.', ',', '/', '#', '%', '*', '(', ')', '[', ']', '+', '-', '_', '@', '$', '^', '&', '<', '>', '|', ':', ';', '"', "'");

            $slug = str_replace($special, "", $data['Title']);
            $slug = str_replace(' ', '-', $slug);
            $slug = mb_strtolower($slug);

            $exist = $aR->findBy(['link' => $slug]);
            if ($exist) {
                $this->addFlash(
                    'danger',
                    'Sorry there is post titled like that'
                );
            } else {
                $article = new Articles();

                if ($data['IMG']) {
                    $date = new \DateTime();
                    $imgName = $date->format('Ymd') . '-' . uniqid() . '.' . $data['IMG']->guessExtension();

                    try {
                        $data['IMG']->move(
                            'images/postsImages',
                            $imgName
                        );

                        $article->setImage($imgName);
                    } catch (FileException $e) {
                        $this->addFlash('danger', 'Something went wrong during sending image. Try again');
                        return $this->redirectToRoute('newArticle', []);
                    }
                }

                $user = $uR->findBy(['id' => $user->getId()])[0];

                $article->setTitle(ucfirst($data['Title']));
                $article->setContent($data['Content']);
                $article->setCreatedAt(new \DateTime());
                $article->setUser($user);
                $article->setLink($slug);

                $em->persist($article);
                $em->flush();

                return $this->redirectToRoute('articleShow', [
                    'slug' => $slug
                ]);
            }
        }

        return $this->render('app/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{slug}/like", name="likeArticle")
     */
    public function likeArticle($slug, ArticlesRepository $aR, UserRepository $uR, EntityManagerInterface $em, SessionInterface $session)
    {
        $logged = $session->get('user');
        if ($logged) {
            $article = $aR->findBy(['link' => $slug]);
            if ($article) {
                $login = $logged->getLogin();
                if ($login) {
                    $article = $article[0];

                    $liked = false;
                    $user = $uR->findBy(['Login' => $login])[0];

                    foreach ($article->getLikes() as $like) {
                        if ($user === $like) {
                            $liked = true;
                        }
                    }

                    if ($liked == false) {
                        $article->addLike($user);
                    } else {
                        $article->removeLike($user);
                    }

                    $em->flush();
                    return $this->redirectToRoute('articleShow', ['slug' => $slug]);
                } else {
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
    public function articleShow($slug, EntityManagerInterface $em, UserRepository $uR, SessionInterface $session, ArticlesRepository $aR, Request $req, CommentsRepository $cR)
    {
        $post = $aR->findBy(['link' => $slug]);

        if ($post) {
            $user = $session->get('user');
            if ($post[0]->getImage()) {
                $post[0]->setImage(stream_get_contents($post[0]->getImage()));
            }

            $liked = false;
            if ($user) {
                $user = $uR->findBy(['Login' => $user->getLogin()])[0];
                foreach ($post[0]->getLikes() as $like) {
                    if ($user === $like) {
                        $liked = true;
                    }
                }
            }

            $comments = $cR->findBy(array('Article' => $post[0]->getId()), array('addedAt' => 'DESC'));


            $form = $this->createFormBuilder()
                ->add('Comment', TextType::class, [
                    'attr' => [
                        'class' => 'cinp',
                        'placeholder' => 'Comment content'
                    ]
                ])
                ->add('Submit', SubmitType::class, [
                    'attr' => [
                        'class' => 'csub'
                    ],
                    'label' => 'Add comment'
                ])
                ->getForm();

            $form->handleRequest($req);

            if ($form->isSubmitted() && $form->isValid()) {
                $content = $form->getData();

                $comment = new Comments();
                $comment->setContent($content['Comment']);
                $comment->setAddedAt(new \DateTime());
                $comment->setUser($user);
                $comment->setArticle($post[0]);

                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('articleShow', ['slug' => $slug]);
            }

            return $this->render('app/show.html.twig', [
                'post' => $post[0],
                'comments' => $comments,
                'form' => $form->createView(),
                'liked' => $liked
            ]);
        } else {
            return $this->render('app/show.html.twig', [
                'post' => $post
            ]);
        }
    }

    /**
     * @Route("/search/{value}", name="articleSearch")
     */
    public function search($value, ArticlesRepository $aR)
    {
        $result = $aR->checkIfContain($value);

        return $this->render('app/search.html.twig', [
            'result' => $result,
            'query' => $value
        ]);;
    }
}
