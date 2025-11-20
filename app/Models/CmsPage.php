<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    public static function cmspageDetails($url)
    {
        $cmspageDetails = CmsPage::where('url', $url)->first()->toArray();

        return ['cmspageDetails' => $cmspageDetails];
    }
}
