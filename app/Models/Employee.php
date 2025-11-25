<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'name', 'email', 'position', 'role', 'is_global_staff', 'image', 'sosmed', 'status'];

    protected $casts = ['sosmed' => 'array', 'is_global_staff' => 'boolean', 'status' => 'boolean'];

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'employee_unit')
            ->withPivot(['position', 'start_date', 'end_date'])
            ->withTimestamps();
    }

    public function departmentsHeaded()
    {
        return $this->hasMany(Department::class, 'head_id');
    }
}
