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
            if (sizeof($tags) < 8) {
                for ($i = 0; $i < sizeof($tags); $i++) {
                    $randomTags[] = $tags[$i];
                }
            } else {
                for ($i = 0; $i < 8; $i++) {
                    $randomTags[] = $tags[mt_rand(0, sizeof($tags) - 1)];
                }
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

    public function getGroupedTagsByFirstLetter(): ?array
    {
        $allTags = $this->tagsRepository->findBy([], ['name' => 'ASC']);
        $sortedTags = [];
        $currentFirstLetter = null;

        foreach ($allTags as $tag) {
            if ($currentFirstLetter !== strtolower(substr($tag->getName(), 0, 1))) {
                $currentFirstLetter = strtolower(substr($tag->getName(), 0, 1));
            }

            $sortedTags[$currentFirstLetter][] = $tag;
        }

        return $sortedTags;
    }
}
