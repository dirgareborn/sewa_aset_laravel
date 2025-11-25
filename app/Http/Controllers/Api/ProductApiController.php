<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Unit;

class ProductApiController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('unit.department')->paginate(50));
    }

    public function show(Product $product)
    {
        return response()->json($product->load('unit.department'));
    }

    public function byUnit(Unit $unit)
    {
        return response()->json($unit->products()->get());
    }
}
