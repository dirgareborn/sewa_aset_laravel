<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentApiController extends Controller
{
    public function index()
    {
        return response()->json(Department::with('units.children')->get());
    }

    public function show(Department $department)
    {
        return response()->json($department->load('units.children'));
    }
}
