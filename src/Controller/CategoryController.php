<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Services\DataServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories")
 */
class CategoryController extends AbstractController
{
    private $categoryRepository;
    private $entityManager;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="categoryList")
     */
    function list(): Response {
        return $this->render('category/list.html.twig', [
            'location' => "List of categories",
            'path' => 'categories',
            'pathLink' => 'categoryList',
        ]);
    }

    /**
     * @Route("/api/categories/get")
     */
    public function apiGetCategories(DataServices $dataServices)
    {
        /* $categories = $dataServices->getGroupedByFirstLetter("categories");

        if (sizeof($categories) == 0) {
        return $this->json("There is nothing to show", 404);
        }

        $outputCaregories = [];

        foreach ($categories as $category) {
        $outputCaregories[] = ['id' => $category->getId(), 'name' => $category->getName()];
        } */

        return $this->json(['a' => [['id' => 1, 'name' => 'Art']], 'f' => [['id' => 2, 'name' => 'Food']]]);
    }
}
