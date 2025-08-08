<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Chia sẻ biến $Contacts cho tất cả view trong admin
        View::composer('admin.*', function ($view) {
            $Contacts = Contact::where('is_read', false)->latest()->get();
            $view->with('Contacts', $Contacts);
        });

        // Chia sẻ categories cho header và các layout client
        View::composer(['client.layouts.header', 'client.layouts.app'], function ($view) {
            $categories = Category::where('status', 1)
                                 ->whereNull('parent_id') // Chỉ lấy danh mục cha
                                 ->with('children')
                                 ->orderBy('name')
                                 ->get();
            $view->with('categories', $categories);
        });
    }
}
