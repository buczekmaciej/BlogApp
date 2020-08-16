<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    public function __construct(UserRepository $ur, ArticlesRepository $ar, CommentsRepository $cr, EntityManagerInterface $em)
    {
        $this->ur = $ur;
        $this->ar = $ar;
        $this->cr = $cr;
        $this->em = $em;
    }

    /**
     * @Route("/admin/dashboard", name="adminDashboard")
     */
    public function dashboard()
    {
        $data = $this->ar->adminDash();
        $data['users'] = $this->ur->getNoUsers();

        return $this->render('admin_dashboard/dash.html.twig', [
            'article' => $data
        ]);
    }

    /**
     * @Route("/admin/articles", name="adminArticles")
     */
    public function adminArticles(Request $request)
    {
        $by = $request->query->get('by') ? $request->query->get('by') : 'id';
        $way = $request->query->get('way') ? $request->query->get('way') : 'ASC';

        return $this->render('admin_dashboard/article/articles.html.twig', [
            'articles' => $this->ar->filterData($by, $way)
        ]);
    }

    /**
     * @Route("/admin/a/edit/{id}", name="adminEditArticle")
     */
    public function adminEditArticle(int $id, Request $request, ParameterBagInterface $pb)
    {
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);
        $post = $this->ar->findOneBy(['id' => $id]);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $post->setTitle($data['title']);
                $post->setContent($data['content']);

                if ($data['image']) {
                    if ($post->getImage()) {
                        $fs = new Filesystem();
                        $fs->remove("{$pb->get('kernel.project_dir')}/public/images/postsImages/{$post->getImage()}");
                    }

                    $newName = $post->getId() . '.' . $data['image']->guessExtension();

                    $data['image']->move(
                        'images/postsImages/',
                        $newName
                    );

                    $post->setImage($newName);
                }

                $this->em->flush();
                return $this->redirectToRoute('adminArticles', []);
            } catch (FileException $e) {
                $this->addFlash('danger', "Uploading failed. Error: {$e->getMessage()}");
                return $this->redirectToRoute('adminArticles', []);
            }
        }

        return $this->render('admin_dashboard/article/edit.html.twig', [
            'article' => $form->createView(),
            'post' => $post
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="adminArticleRemove", methods={"POST"})
     */
    public function admArticleRemove(int $id, ParameterBagInterface $pb)
    {
        try {
            $post = $this->ar->findOneBy(['id' => $id]);
            foreach ($post->getComments() as $comm) $this->em->remove($comm);

            if ($post->getImage()) {
                $fs = new Filesystem();
                $fs->remove("{$pb->get('kernel.project_dir')}/public/images/postsImages/{$post->getImage()}");
            }

            $this->em->remove($post);
            $this->em->flush();
            return new Response();
        } catch (QueryException $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * @Route("/admin/comments", name="adminComments")
     */
    public function adminComments(PaginatorInterface $pag, Request $request)
    {
        $by = $request->query->get('by') ? $request->query->get('by') : 'id';
        $way = $request->query->get('way') ? $request->query->get('way') : 'ASC';

        return $this->render('admin_dashboard/comment/comments.html.twig', [
            'comments' => $pag->paginate($this->cr->filterData($by, $way), $request->query->getInt('page', 1), 15)
        ]);
    }

    /**
     * @Route("/admin/comment/{id}", name="adminCommentsRemove", methods={"POST"})
     */
    public function admCommentsRemove(int $id)
    {
        try {
            $this->em->remove($this->cr->findOneBy(['id' => $id]));
            $this->em->flush();
            return new Response();
        } catch (QueryException $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * @Route("/admin/users", name="adminUsers")
     */
    public function adminUsers(PaginatorInterface $pag, Request $request)
    {
        $by = $request->query->get('by') ? $request->query->get('by') : 'id';
        $way = $request->query->get('way') ? $request->query->get('way') : 'ASC';

        return $this->render('admin_dashboard/user/users.html.twig', [
            'users' => $pag->paginate($this->ur->filterData($by, $way), $request->query->getInt('page', 1), 15)
        ]);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="adminDeleteUser", methods={"POST"})
     */
    public function admDeleteUser(int $id)
    {
        try {
            $this->em->remove($this->ur->findOneBy(['id' => $id]));
            $this->em->flush();
            return new Response();
        } catch (QueryException $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * @Route("/admin/user/{id}/promote", name="adminPromote")
     */
    public function admPromote(int $id)
    {
        try {
            $user = $this->ur->findOneBy(['id' => $id]);
            if (in_array("ROLE_ADMIN", $user->getRoles()) && !in_array("ROLE_SUPER_ADMIN", $user->getRoles())) $user->setRoles(["ROLE_USER", "ROLE_ADMIN", "ROLE_SUPER_ADMIN"]);
            if (!in_array("ROLE_ADMIN", $user->getRoles())) $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
            $this->em->flush();
            return $this->redirectToRoute('adminUsers', []);;
        } catch (QueryException $e) {
            throw $e->getMessage();
        }
    }

    /**
     * @Route("/admin/user/{id}/demote", name="adminDemote")
     */
    public function admDemote(int $id)
    {
        try {
            $user = $this->ur->findOneBy(['id' => $id]);
            if (in_array("ROLE_ADMIN", $user->getRoles()) && count($user->getRoles()) == 2) $user->setRoles(["ROLE_USER"]);
            if (in_array("ROLE_SUPER_ADMIN", $user->getRoles())) $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
            $this->em->flush();
            return $this->redirectToRoute('adminUsers', []);;
        } catch (QueryException $e) {
            throw $e->getMessage();
        }
    }

    /**
     * @Route("/admin/user/{id}/disable", name="adminDisable")
     */
    public function admDisable(int $id)
    {
        try {
            $user = $this->ur->findOneBy(['id' => $id]);
            $user->setIsDisabled(!$user->getIsDisabled());
            $this->em->flush();
            return $this->redirectToRoute('adminUsers', []);
        } catch (QueryException $e) {
            return new Response($e->getMessage());
        }
    }
}
