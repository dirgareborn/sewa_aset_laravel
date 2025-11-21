<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Department;
use Illuminate\Http\Request;

class UnitApiController extends Controller
{
    public function index()
    {
        return response()->json(Unit::with('department','children')->get());
    }

    public function show(Unit $unit)
    {
        return response()->json($unit->load('department','children','products'));
    }

    public function byDepartment(Department $department)
    {
        $units = Unit::with('children')->where('department_id', $department->id)->get();
        return response()->json($units);
    }
}
