<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name','type','head_id','status'];

    public function head() {
        return $this->belongsTo(Employee::class,'head_id');
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
