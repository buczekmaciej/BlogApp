<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArticlesRepository;
use App\Repository\UserRepository;
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
    public function removePost($id, ArticlesRepository $aR, EntityManagerInterface $em)
    {
        $post = $aR->findBy(['id'=>$id]);

        if($post)
        {
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
}
