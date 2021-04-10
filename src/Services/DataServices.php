<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Component\Security\Core\Security;

class DataServices
{
    private $security;
    private $articleRepository;
    private $categoriesRepository;
    private $tagsRepository;

    public function __construct(Security $security, ArticleRepository $articleRepository, CategoryRepository $categoriesRepository, TagRepository $tagsRepository)
    {
        $this->security = $security;
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
