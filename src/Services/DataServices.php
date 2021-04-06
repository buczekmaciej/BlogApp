<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;

class DataServices
{
    private $articleRepository;
    private $categoriesRepository;
    private $tagsRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoriesRepository, TagRepository $tagsRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagsRepository = $tagsRepository;
    }

    public function getMostRecentArticles(): array
    {
        return $this->articleRepository->findBy([], ['postedAt' => 'DESC'], 10);
    }

    public function getMostPopularCategories(): array
    {
        return $this->categoriesRepository->getMostPopularCategories();
    }

    public function getRandomTags(): ?array
    {
        $tags = $this->tagsRepository->findAll();
        $randomTags = [];

        if ($tags) {
            for ($i = 0; $i < 8; $i++) {
                $randomTags[] = $tags[$i];
            }
        }

        return $randomTags;
    }

    public function getSearchedData(string $query)
    {
        return [
            'articles' => $this->articleRepository->getArticlesContainingString($query),
            'categories',
        ];
    }
}
