<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get main categories for navigation
        $navCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $view->with('navCategories', $navCategories);
    }
}
