<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    /**
     * Get navigation structure
     */
    private function getNavigationStructure()
    {
        return [
            'index' => [
                'title' => 'API Documentation',
                'url' => route('docs.index'),
                'order' => 1
            ],
            'getting-started' => [
                'title' => 'Getting Started',
                'url' => route('docs.getting-started'),
                'order' => 2
            ],
            'authentication' => [
                'title' => 'Authentication',
                'url' => route('docs.authentication'),
                'order' => 3
            ],
            'auth-endpoints' => [
                'title' => 'Authentication Endpoints',
                'url' => route('docs.endpoints', 'auth'),
                'order' => 4
            ],
            'user-endpoints' => [
                'title' => 'User Management',
                'url' => route('docs.endpoints', 'users'),
                'order' => 5
            ],
            'product-endpoints' => [
                'title' => 'Products API',
                'url' => route('docs.endpoints', 'products'),
                'order' => 6
            ],
            'category-endpoints' => [
                'title' => 'Categories API',
                'url' => route('docs.endpoints', 'categories'),
                'order' => 7
            ],
            'order-endpoints' => [
                'title' => 'Orders API',
                'url' => route('docs.endpoints', 'orders'),
                'order' => 8
            ],
            'api-tester' => [
                'title' => 'API Tester',
                'url' => route('docs.api-tester'),
                'order' => 9
            ]
        ];
    }

    /**
     * Get previous and next page for navigation
     */
    private function getPageNavigation($currentPageKey)
    {
        $navigation = $this->getNavigationStructure();
        $pages = collect($navigation)->sortBy('order');

        $currentIndex = $pages->search(function ($page, $key) use ($currentPageKey) {
            return $key === $currentPageKey;
        });

        $previousPage = null;
        $nextPage = null;

        if ($currentIndex !== false) {
            $pageKeys = $pages->keys()->toArray();
            $currentPosition = array_search($currentPageKey, $pageKeys);

            if ($currentPosition > 0) {
                $prevKey = $pageKeys[$currentPosition - 1];
                $previousPage = $navigation[$prevKey];
            }

            if ($currentPosition < count($pageKeys) - 1) {
                $nextKey = $pageKeys[$currentPosition + 1];
                $nextPage = $navigation[$nextKey];
            }
        }

        return [
            'previousPage' => $previousPage,
            'nextPage' => $nextPage
        ];
    }

    /**
     * API Documentation bosh sahifasi
     */
    public function index()
    {
        $navigation = $this->getPageNavigation('index');

        $data = [
            'title' => 'API Documentation',
            'version' => '1.3.5',
            'baseUrl' => config('app.url') . '/api',
            'description' => 'DOM Product Project API Documentation - Complete guide for developers',
            'navigation' => $navigation
        ];

        return view('docs.index', $data);
    }

    /**
     * Boshlash bo'yicha qo'llanma
     */
    public function gettingStarted()
    {
        $navigation = $this->getPageNavigation('getting-started');

        $data = [
            'title' => 'Getting Started',
            'baseUrl' => config('app.url') . '/api',
            'navigation' => $navigation
        ];

        return view('docs.getting-started', $data);
    }

    /**
     * Autentifikatsiya dokumentatsiyasi
     */
    public function authentication()
    {
        $navigation = $this->getPageNavigation('authentication');

        $data = [
            'title' => 'Authentication',
            'endpoints' => [
                'register' => '/api/register',
                'login' => '/api/login',
                'logout' => '/api/logout',
                'refresh' => '/api/refresh'
            ],
            'navigation' => $navigation
        ];

        return view('docs.authentication', $data);
    }

    /**
     * API Endpoints bo'limlari
     */
    public function endpoints($section)
    {
        $sections = [
            'auth' => [
                'title' => 'Authentication Endpoints',
                'description' => 'User authentication and authorization endpoints'
            ],
            'users' => [
                'title' => 'User Management',
                'description' => 'User profile and management endpoints'
            ],
            'products' => [
                'title' => 'Products API',
                'description' => 'Product management and catalog endpoints'
            ],
            'categories' => [
                'title' => 'Categories API',
                'description' => 'Product categories management'
            ],
            'orders' => [
                'title' => 'Orders API',
                'description' => 'Order management and processing'
            ]
        ];

        if (!isset($sections[$section])) {
            abort(404);
        }

        $navigationKey = $section . '-endpoints';
        $navigation = $this->getPageNavigation($navigationKey);

        $data = array_merge($sections[$section], [
            'section' => $section,
            'baseUrl' => config('app.url') . '',
            'navigation' => $navigation
        ]);

        return view("docs.endpoints.{$section}", $data);
    }

    /**
     * API testing sahifasi
     */
    public function apiTester()
    {
        $navigation = $this->getPageNavigation('api-tester');

        $data = [
            'title' => 'API Tester',
            'baseUrl' => config('app.url') . '',
            'navigation' => $navigation
        ];

        return view('docs.api-tester', $data);
    }
}
