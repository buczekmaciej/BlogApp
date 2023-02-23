<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NavigationLinksProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $links = [
            [
                'routeGroup' => 'app.*',
                'route' => 'app.homepage',
                'routeName' => 'Home'
            ],
            [
                'routeGroup' => 'articles.*',
                'route' => 'articles.list',
                'routeName' => 'Articles'
            ],
            [
                'routeGroup' => 'authors.*',
                'route' => 'authors.list',
                'routeName' => 'Authors'
            ],
            [
                'routeGroup' => 'tags.*',
                'route' => 'tags.list',
                'routeName' => 'Tags'
            ],
        ];

        view()->composer('*', function ($view) use ($links) {
            if (!in_array($view->getName(), ['base', 'navigation', 'security.login', 'security.register'])) {
                $view->with('links', $links);
            }
        });
    }
}
