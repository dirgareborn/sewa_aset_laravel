<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryController extends Controller
{
    use HasFactory;

    public function category()
    {

        $page_title = 'KATEGORI';
        $categories = Category::with('subcategories')->orderBy('category_name')->get();

        // dd($layanan);
        return view('front.pages.categories', compact('page_title', 'categories'));
    }
}
