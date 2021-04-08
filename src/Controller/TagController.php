<?php

namespace App\Controller;

use App\Form\TagType;
use App\Repository\TagRepository;
use App\Services\DataServices;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tags")
 */
class TagController extends AbstractController
{
    private $entityManager;
    private $tagRepository;

    public function __construct(EntityManagerInterface $entityManager, TagRepository $tagRepository)
    {
        $this->entityManager = $entityManager;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @Route("/", name="tagList")
     */
    function list(DataServices $dataServices): Response {
        return $this->render('tag/list.html.twig', [
            'location' => 'List of tags',
            'path' => 'Tags',
            'pathLink' => 'tagList',
            'tags' => $dataServices->getGroupedTagsByFirstLetter(),
        ]);
    }

    /**
     * @Route("/create", name="tagCreate")
     */
    public function create(?string $error = null, Request $request): Response
    {
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(TagType::class, null, ['tagName' => null]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            if (!$this->tagRepository->findOneBy(['name' => $tag->getName()])) {
                $this->entityManager->persist($tag);
                $this->entityManager->flush();

                return $this->redirectToRoute('tagList');
            } else {
                $error = "Such tag already exists";
            }
        }

        return $this->render('tag/create.html.twig', [
            'location' => 'Create tag',
            'path' => 'Tags',
            'pathLink' => 'tagList',
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tagEdit")
     */
    public function edit(int $id, ?string $error = null, Request $request): Response
    {
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('home');
        }
        $currentTag = $this->tagRepository->findOneBy(['id' => $id]);

        if (!$currentTag) {
            throw new NoResultException("No result was found to fulfill form requirements");
        }

        $form = $this->createForm(TagType::class, null, ['tagName' => $currentTag->getName()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newName = $form->getData()->getName();
            $existsTagWithNewName = $this->tagRepository->findOneBy(['name' => $newName]) ? true : false;

            if (!$existsTagWithNewName) {
                $currentTag->setName($newName);
                $this->entityManager->flush();

                return $this->redirectToRoute('tagList');
            } else {
                $error = "Such tag already exists";
            }
        }

        return $this->render('tag/edit.html.twig', [
            'location' => 'Edit tag',
            'path' => 'Tags',
            'pathLink' => 'tagList',
            'form' => $form->createView(),
            'error' => $error,
            'currentName' => $currentTag->getName(),
        ]);
    }
}
