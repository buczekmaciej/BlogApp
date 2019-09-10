<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArticlesRepository;
use App\Repository\UserRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="adminDashboard")
     */
    public function dashboard()
    {
        return $this->render('admin_dashboard/dash.html.twig', []);
    }

    /**
     * @Route("/admin/dashboard/logout", name="dashLogout")
     */
    public function dashLogout()
    {
        $this->get('security.token_storage')->setToken(null);
    
        return $this->redirectToRoute('appHomepage', []);
    }

    /**
     * @Route("/admin/manage/posts", name="managePosts")
     */
    public function managePosts(ArticlesRepository $aR)
    {
        $posts = $aR->findBy(array(), array('createdAt'=>'DESC'));

        return $this->render('admin_dashboard/postManage.html.twig', [
            'posts'=>$posts
        ]);
    }

    /**
     * @Route("/admin/post/{id}/remove", name="removePost")
     */
    public function removePost($id, ArticlesRepository $aR, CommentsRepository $cR, EntityManagerInterface $em)
    {
        $post = $aR->findBy(['id'=>$id]);

        if($post)
        {
            $comments = $cR->findBy(['Article'=>$post[0]->getId()]);
            foreach($comments as $comment)
            {
                $em->remove($comment);
            }
            $em->remove($post[0]);
            $em->flush();
            $this->addFlash('susccess','Post has been removed');
        }

        return $this->redirectToRoute('managePosts', []);
    }

    /**
     * @Route("/admin/manage/users", name="manageUsers")
     */
    public function manageUsers(UserRepository $uR)
    {
        $users = $uR->findBy(array(), array('Login'=>'ASC'));

        return $this->render('admin_dashboard/users.html.twig', [
            'users'=>$users
        ]);
    }
    
    /**
     * @Route("/admin/u/{id}/disable", name="disableUser")
     */
    public function disableUser($id, EntityManagerInterface $em, UserRepository $uR)
    {
        $user = $uR->findBy(['id'=>$id]);

        if($user)
        {
            $user[0]->setIsDisabled(true);
            $em->flush();
            $this->addFlash('susccess','User has been disabled');
        }

        return $this->redirectToRoute('manageUsers', []);
    }
    
    /**
     * @Route("/admin/u/{id}/enable", name="enableUser")
     */
    public function enableUser($id, EntityManagerInterface $em, UserRepository $uR)
    {
        $user = $uR->findBy(['id'=>$id]);

        if($user)
        {
            $user[0]->setIsDisabled(false);
            $em->flush();
            $this->addFlash('susccess','User has been enabled');
        }

        return $this->redirectToRoute('manageUsers', []);
    }

    /**
     * @Route("/admin/manage/comments", name="manageComments")
     */
    public function manageComments(CommentsRepository $cR)
    {
        $comments = $cR->findBy(array(), array('addedAt'=>'DESC'));

        return $this->render('admin_dashboard/comments.html.twig', [
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("/admin/comment/{id}/remove", name="removeComment")
     */
    public function removeComment($id, CommentsRepository $cR, EntityManagerInterface $em)
    {
        $comment = $cR->findBy(['id'=>$id]);
        if($comment)
        {
            $em->remove($comment[0]);
            $em->flush();
            $this->addFlash('success','Comment has been successfully removed');
        }
        return $this->redirectToRoute('manageComments', []);
    }
}
