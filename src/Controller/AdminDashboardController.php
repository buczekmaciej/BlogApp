<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use App\Repository\UserRepository;
use App\Services\convertNumbers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    public function __construct(UserRepository $ur, ArticlesRepository $ar, CommentsRepository $cr, convertNumbers $conv)
    {
        $this->ur = $ur;
        $this->ar = $ar;
        $this->cr = $cr;
        $this->conv = $conv;
    }

    /**
     * @Route("/admin/dashboard", name="adminDashboard")
     */
    public function dashboard()
    {
        $data = $this->ar->adminDash();
        $data['users'] = $this->conv->convert($this->ur->getNoUsers());
        $data['likes'] = $this->conv->convert($data['likes']);
        $data['comments'] = $this->conv->convert($data['comments']);

        return $this->render('admin_dashboard/dash.html.twig', [
            'article' => $data
        ]);
    }

    /**
     * @Route("/admin/articles", name="adminArticles")
     */
    public function adminArticles()
    {
        //TODO: Filter
        //TODO: Paths for actions
        return $this->render('admin_dashboard/articles.html.twig', [
            'articles' => $this->ar->findAll()
        ]);
    }
}
