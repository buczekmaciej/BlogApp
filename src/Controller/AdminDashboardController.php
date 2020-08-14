<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use App\Repository\UserRepository;
use App\Services\convertNumbers;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    public function __construct(UserRepository $ur, ArticlesRepository $ar, CommentsRepository $cr, convertNumbers $conv, EntityManagerInterface $em)
    {
        $this->ur = $ur;
        $this->ar = $ar;
        $this->cr = $cr;
        $this->em = $em;
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
    public function adminArticles(Request $request)
    {
        //TODO: Filter
        //TODO: Path for edit
        $by = $request->query->get('by') ? $request->query->get('by') : 'id';
        $way = $request->query->get('way') ? $request->query->get('way') : 'ASC';

        return $this->render('admin_dashboard/articles.html.twig', [
            'articles' => $this->ar->findBy([], [$by => $way])
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
}
