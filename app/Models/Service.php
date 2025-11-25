<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id','location_id', 'name', 'description', 'price', 'is_price_per_type', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
