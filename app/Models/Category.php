<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'organization_id',
        'category_name',
        'url',
        'category_image',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employments')
            ->withPivot('position', 'start_date', 'end_date', 'status')
            ->withTimestamps();
    }

    public static function getCategories()
    {
        return Category::with(['parent' => function ($query) {
            $query->with('parent');
        }])
            ->whereNull('parent_id')     // ⬅ ini solusi
            ->where('status', 1)
            ->get()
            ->toArray();
    }

    public static function categoryDetails($url)
    {
        $categoryDetails = Category::select('id', 'parent_id', 'category_name', 'url')
            ->with('children')
            ->where('url', $url)
            ->first()
            ->toArray();
        // echo "<pre>"; print_r($categoryDetails);die;
        $catIds = [];
        $catIds[] = $categoryDetails['id'];
        foreach ($categoryDetails['children'] as $subcat) {
            $catIds[] = $subcat['id'];
        }
        if ($categoryDetails['parent_id'] == null) {
            // Only show main category
            $breadcrumbs = '';
        } else {
            $parentCategory = Category::select('category_name', 'url')->where('id', $categoryDetails['parent_id'])->first()->toArray();
        }

        return ['catIds' => $catIds, 'categoryDetails' => $categoryDetails];
    }

    public function products(): hasMany
    {
        return $this->hasMany(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->url = Str::slug($category->category_name, '-');
        });
        static::updating(function ($category) {
            $category->url = Str::slug($category->category_name, '-');
        });
    }
}
