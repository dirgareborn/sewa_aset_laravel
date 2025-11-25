<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Unit;
use App\Models\Page;
use App\Models\ProfilWebsite;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // =========================
        // Blade directives
        // =========================
        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });

        // =========================
        // Footer Gallery
        // =========================
        View::composer('front.partials.footer', function ($view) {
            $galery = Cache::remember('image', 3600, function () {
                return Service::with('slides:image,service_id')
                                            ->select('id', 'slug')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
            });
            $view->with('galery', $galery);
        });

        // =========================
        // Sidebar Menu Categories
        // =========================
        View::composer('front.partials.navbar', function ($view) {
            $MenuCategories = Cache::remember('categories', 3600, function () {
                return Unit::with('children')
                    ->orderBy('parent_id')
                    ->orderBy('name')
                    ->get();
            });
            // dd($MenuCategories);
            $view->with('MenuCategories', $MenuCategories);
        });

        // =========================
        // Quick Links (CMS Pages)
        // =========================
        View::composer('front.partials.footer', function ($view) {
            $links = ['tentang-kami', 'kontak-kami', 'kebijakan-privasi'];
            $QuickLinks = Cache::remember('cms_pages', 3600, function () use ($links) {
                return Page::select('url', 'title')->whereIn('url', $links)->get();
            });
            $view->with('QuickLinks', $QuickLinks);
        });

        // =========================
        // Profil Website
        // =========================
        View::composer('front.partials.footer', function ($view) {
            $profil = Cache::remember('profil_website', 3600, function () {
                return ProfilWebsite::first();
            });
            $view->with('profil', $profil);
        });

        // =========================
        // Cart Items per User / Session
        // =========================
        View::composer('*', function ($view) {
            $session_id = Session::get('session_id') ?? Session::getId();
            Session::put('session_id', $session_id);

            $user_id = Auth::check() ? Auth::id() : 0;

            // Update cart user_id jika login
            if ($user_id) {
                Cart::where('session_id', $session_id)->update(['user_id' => $user_id]);
            }

            // Ambil cart items per user/session, cache sebentar 1 menit
            $cacheKey = $user_id ? "cart_items_user_{$user_id}" : "cart_items_session_{$session_id}";

            $cartItems = cartItems();
            $totalCartItems = totalCartItems();
            $view->with(compact('cartItems', 'totalCartItems'));
        });

        // =========================
        // Shared Blade variable (example)
        // =========================
        View::share('key', 'value');
    }
}
