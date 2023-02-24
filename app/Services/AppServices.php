<?php

namespace App\Services;

use App\Models\Article;

class AppServices
{
    public function getHomepageArticles(): array
    {
        $latest = Article::orderBy('created_at', 'DESC')->limit(9)->get();

        return [
            'latest' => $latest->first(),
            'articles' => $latest->slice(1),
        ];
    }

    public function getPopularTags()
    {
    }

    public function getSearchMatchingData(string $q): array
    {
        return [];
    }
}
