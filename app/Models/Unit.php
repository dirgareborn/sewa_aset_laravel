<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'parent_id', 'name', 'slug', 'type', 'description', 'is_active'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_unit')
            ->withPivot(['position', 'start_date', 'end_date'])
            ->withTimestamps();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
