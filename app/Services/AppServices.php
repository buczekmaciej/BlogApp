<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;

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

    public function getPopularTags(): array
    {
        return [
            'recent' => $this->getRecentlyActiveTags(),
            'active' => Tag::withCount('articles')->orderBy('articles_count', 'desc')->having('articles_count', '>', '0')->limit(10)->get()
        ];
    }

    public function getRecentlyActiveTags(): array
    {
        $lastMonthArticles = Article::where('created_at', '>', Carbon::now()->subMonth())->get();
        $tagsUsed = [];

        foreach ($lastMonthArticles as $article) {
            $tagsUsed[] = $article->tags()->pluck('name')->toArray()[0];
        }

        $tagsUsed = array_count_values($tagsUsed);

        uasort($tagsUsed, fn ($a, $b) => $b - $a);

        return array_slice($tagsUsed, 0);
    }

    public function getSearchMatchingData(string $q): array
    {
        return [
            'tags' => Tag::where('name', 'LIKE', "%$q%")->get(),
            'authors' => User::where('roles', 'LIKE', '%WRITER%')->where('username', 'LIKE', "%$q%")->get(),
            'articles' => Article::where('title', 'LIKE', "%$q%")->paginate(9)
        ];
    }
}
