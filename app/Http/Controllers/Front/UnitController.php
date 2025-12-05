<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitController extends Controller
{
    use HasFactory;

    public function category()
    {

        $page_title = 'KATEGORI';
        $categories = Unit::with('children')->orderBy('name')->get();

        return view('front.pages.categories', compact('page_title', 'categories'));
    }
}
