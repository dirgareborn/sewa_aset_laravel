<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id','location_id', 'name', 'description', 'base_price', 'is_price_per_type', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

       public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function slides(): hasMany
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ServicePrice::class);
    }

    public function accountBanks()
    {
        return $this->hasMany(AccountBank::class);
    }
}
