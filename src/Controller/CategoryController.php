<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Services\DataServices;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/get")
     */
    public function apiGetCategories(DataServices $dataServices)
    {
        $group = $dataServices->getGroupedByFirstLetter("categories");

        if (sizeof($group) == 0) {
            return $this->json("There is nothing to show", 404);
        }

        $outputCaregories = [];

        foreach ($group as $letter => $categories) {
            foreach ($categories as $category) {
                $outputCaregories[$letter][] = ['id' => $category->getId(), 'name' => $category->getName()];
            }
        }

        return $this->json($outputCaregories);
    }

    /**
     * @Route("/create", name="categoryCreate")
     */
    public function create(?string $error = null, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, null, ['categoryName' => null]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            if (!$this->categoryRepository->findOneBy(['name' => $category->getName()])) {
                $this->entityManager->persist($category);
                $this->entityManager->flush();

                return $this->redirectToRoute('categoryList');
            } else {
                $error = "Such named category already exists";
            }
        }

        return $this->render('category/create.html.twig', [
            'location' => 'Create category',
            'path' => 'Categories',
            'pathLink' => 'categoryList',
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categoryEdit")
     */
    public function edit(int $id, ?string $error = null, Request $request): Response
    {
        $oldData = $this->categoryRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(CategoryType::class, null, ['categoryName' => $oldData->getName()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            if ($oldData->getName() !== $category->getName()) {
                if ($this->categoryRepository->findOneBy(['name' => $category->getName()])) {
                    $error = "Such named category already exists";
                }
            }

            if (!$error) {
                $oldData->getTags()->clear();
                foreach ($category->getTags() as $tag) {
                    $oldData->addTag($tag);
                }
                $this->entityManager->flush();

                return $this->redirectToRoute('categoryList');
            }
        }

        return $this->render('category/edit.html.twig', [
            'location' => 'Edit category',
            'path' => 'Categories',
            'pathLink' => 'categoryList',
            'form' => $form->createView(),
            'error' => $error,
            'oldTags' => $oldData->getTags(),
        ]);
    }

    /**
     * @Route("/{name}", name="categoryView")
     */
    public function view(string $name, PaginatorInterface $paginator, Request $request): Response
    {
        $category = $this->categoryRepository->findOneBy(['name' => $name]);
        $articlesFromCategory = $category->getArticles();
        $authorsCreatingInCategory = [];

        foreach ($articlesFromCategory as $article) {
            $authorsCreatingInCategory[] = $article->getAuthor();
        }

        $authorsCreatingInCategory = array_unique($authorsCreatingInCategory, SORT_REGULAR);

        return $this->render('category/view.html.twig', [
            'location' => $name,
            'path' => 'Categories',
            'pathLink' => 'categoryList',
            'articles' => $paginator->paginate($articlesFromCategory, $request->query->getInt("page", 1), 15),
            'authors' => $authorsCreatingInCategory,
        ]);
    }

    /**
     * @Route("/{name}/authors", name="categoryAuthors")
     */
    public function authors(string $name): Response
    {
        $articlesFromCategory = $this->categoryRepository->findOneBy(['name' => $name])->getArticles();
        $authorsCreatingInCategory = [];

        foreach ($articlesFromCategory as $article) {
            $authorsCreatingInCategory[] = $article->getAuthor();
        }

        $authorsCreatingInCategory = array_unique($authorsCreatingInCategory, SORT_REGULAR);

        return $this->render('category/authors.html.twig', [
            'location' => $name . " authors",
            'path' => 'Categories',
            'pathLink' => 'categoryList',
            'authors' => $authorsCreatingInCategory,
        ]);
    }
}
