<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;

class DataServices
{
    private $articleRepository;
    private $categoriesRepository;
    private $tagsRepository;
    private $userRespository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoriesRepository, TagRepository $tagsRepository, UserRepository $userRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagsRepository = $tagsRepository;
        $this->userRespository = $userRepository;
    }

    public function getMostRecentArticles(): array
    {
        return $this->articleRepository->findBy(['status' => false], ['postedAt' => 'DESC'], 10);
    }

    public function getMostPopularCategories(): array
    {
        return $this->categoriesRepository->getMostPopularCategories();
    }

    public function getSearchedData(string $query)
    {
        return [
            'authors' => $this->userRespository->getMatchingUsers($query),
            'categories' => $this->categoriesRepository->getCategoriesMatching($query),
            'articles' => $this->articleRepository->getArticlesContainingString($query),
        ];
    }

    public function getGroupedByFirstLetter(string $option): ?array
    {
        if (!in_array($option, ['tags', 'categories'])) {
            return null;
        }

        $allData = $option == "tags" ? $this->tagsRepository->findBy([], ['name' => 'ASC']) : $this->categoriesRepository->findBy([], ['name' => 'ASC']);
        $groupedOutput = [];
        $currentFirstLetter = null;

        foreach ($allData as $object) {
            if ($currentFirstLetter !== strtolower(substr($object->getName(), 0, 1))) {
                $currentFirstLetter = strtolower(substr($object->getName(), 0, 1));
            }

            $groupedOutput[$currentFirstLetter][] = $object;
        }

        return $groupedOutput;
    }
}
