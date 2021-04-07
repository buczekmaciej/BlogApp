<?php

namespace App\Controller;

use App\Services\DataServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $dataServices;

    public function __construct(DataServices $dataServices)
    {
        $this->dataServices = $dataServices;
    }

    /**
     * @Route("/", name="home")
     */
    public function homepage(): Response
    {
        return $this->render('app/homepage.html.twig', [
            'location' => "Homepage",
            'path' => null,
            'recentArticles' => $this->dataServices->getMostRecentArticles(),
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        return $this->render('app/search.html.twig', [
            'location' => "Results for {$request->query->get('q')}",
            'path' => 'Search',
            'pathLink' => 'search',
            'data' => $this->dataServices->getSearchedData($request->query->get('q')),
        ]);
    }
}
