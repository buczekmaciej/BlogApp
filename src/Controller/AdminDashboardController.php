<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="adminDashboard")
     */
    public function dashboard()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('admin_dashboard/dash.html.twig', [
            'user'=>$user
        ]);
    }

}
