<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AdminLinksProvider extends ServiceProvider
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
                'routeGroup' => 'admin.dashboard',
                'route' => 'admin.dashboard',
                'routeName' => 'Dashboard',
                'arguments' => [],
            ],
            [
                'routeGroup' => 'admin.articles.*',
                'route' => 'admin.articles.list',
                'routeName' => 'Articles',
                'arguments' => [],
            ],
            [
                'routeGroup' => 'admin.users.*',
                'route' => 'admin.users.list',
                'routeName' => 'Users',
                'arguments' => [],
            ],
            [
                'routeGroup' => 'admin.tags.*',
                'route' => 'admin.tags.list',
                'routeName' => 'Tags',
                'arguments' => [],
            ],
            [
                'routeGroup' => 'admin.comments.*',
                'route' => 'admin.comments.list',
                'routeName' => 'Comments',
                'arguments' => [],
            ],
            [
                'routeGroup' => 'admin.warrants.*',
                'route' => 'admin.warrants.list',
                'routeName' => 'Warrants',
                'arguments' => [],
            ],
            [
                'routeGroup' => 'admin.reports.*',
                'route' => 'admin.reports.list',
                'routeName' => 'Reports',
                'arguments' => [],
            ],
        ];

        view()->composer('components.admin-nav', function ($view) use ($links) {
            $view->with('links', $links);
        });
    }
}
