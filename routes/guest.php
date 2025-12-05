<?php

use App\Http\Controllers\Front\DocumentController;
use App\Http\Controllers\Front\InformationController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\Front\NewsletterController;
use App\Models\Page;
use App\Models\Unit;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Carbon;
// Frontend routes

// Front Public

Route::namespace('App\Http\Controllers\Front')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('beranda');

    // Listing Categories Route
    // Category all
    Route::get('unit-bisnis', 'CategoryController@category');

    // Dynamic category & search
    Route::get('/unit-bisnis/{slug?}', [ServiceController::class, 'listing'])
        ->name('categories.listing');

    // Product Detail ini masih mau di ubah
    Route::get('unit-bisnis/{unit}/{slug}', 'ProductController@show');
    // Product Search
    Route::get('search-products', 'ProductController@listing');
    Route::get('/search', [SearchController::class, 'index'])->name('global.search');

    // Page
    Route::get('/visi-misi', [PageController::class, 'visiMisi'])->name('visi-misi');

    Route::get('/kontak-kami', [PageController::class, 'kontak'])->name('kontak-kami');

    Route::post('/kontak-kami/kirim', [PageController::class, 'kirimKontak'])->name('kontak.kirim');

    Route::get('/struktur-organisasi', [PageController::class, 'strukturOrganisasi'])->name('struktur-organisasi');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
    Route::get('/kebijakan-privasi', [PageController::class, 'cookies'])->name('cookies');

    // Listing Page Route
   Route::get('{page}', 'PageController@show');
    

Route::get('/dokumen', [DocumentController::class, 'index'])->name('dokumen.index');
Route::get('/dokumen/preview/{filename}', [DocumentController::class, 'preview'])
    ->where('filename', '.*')
    ->name('dokumen.preview');
Route::get('kerjasama', function () {

    return view('front.partials.kerjasama');
})->name('kerjasama');
Route::get('help-desk', function () {

    return view('front.openai');
})->name('help-desk');
Route::get('/informasi', [InformationController::class, 'index'])->name('informasi.index');
Route::get('/informasi/{slug}', [InformationController::class, 'show'])->name('informasi.show');
Route::post('/newsletter/store', [NewsletterController::class, 'store'])->name('newsletter.store');

Route::get('/sitemap', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create('/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1))
        ->add(Url::create('/visi-misi'))
        ->add(Url::create('/struktur-organisasi'))
        ->add(Url::create('/kontak-kami'))
        ->add(Url::create('/register'))
        ->add(Url::create('/login'))
        ->add(Url::create('/tentang-kami'))
        ->add(Url::create('/kebijakan-cookies'));

    $cats = Unit::all()->each(function (Unit $unit) use ($sitemap) {
        $url = $unit->url;
        $sitemap->add(Url::create('/kategori/'.$url)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1));
    });
    $sitemap->writeToFile(public_path('sitemap.xml'));

    return 'success';
});
});
