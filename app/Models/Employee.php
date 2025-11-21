<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'email',
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'image',
        'sosmed',
        'status',
        'position',
    ];

    protected $casts = [
        'sosmed' => 'array',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function categories() {
        return $this->belongsToMany(Category::class,'employments')
                    ->withPivot('position','start_date','end_date','status')
                    ->withTimestamps();
    }

    public function departments() {
        return $this->hasMany(Organization::class,'head_id');
    }
}
