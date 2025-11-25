<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    public static function cmspageDetails($url)
    {
        $cmspageDetails = Page::where('url', $url)->first()->toArray();

        return ['cmspageDetails' => $cmspageDetails];
    }
}
