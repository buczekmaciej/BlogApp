<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    private $entityManager;
    private $articleRespository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRespository = $articleRepository;
    }

    /**
     * @Route("/", name="adminDashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', []);
    }

    /**
     * @Route("/article/{id}/status-change", name="adminArticleSwitch")
     */
    public function adminArticleSwtich(int $id, Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $article = $this->articleRespository->findOneBy(['id' => $id]);
        $article->setStatus($article->getStatus() === false ? true : false);

        $this->entityManager->flush();

        return $this->redirect($urlGenerator->generate($request->query->get('return-link'), $request->query->get("return-link") === "articleView" ? ['id' => $id] : []));
    }
}
