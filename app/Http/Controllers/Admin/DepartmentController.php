<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->paginate(20);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:191|unique:departments,name',
            'description'=>'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);
        Department::create($data);

        return redirect()->route('admin.departments.index')->with('success','Department created.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name'=>'required|string|max:191|unique:departments,name,'.$department->id,
            'description'=>'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $department->update($data);

        return redirect()->route('admin.departments.index')->with('success','Department updated.');
    }

    public function destroy(Department $department)
    {
        // Optionally: check units exist and prevent delete
        if ($department->units()->count()) {
            return back()->with('error','Cannot delete department with units. Remove units first.');
        }
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success','Department deleted.');
    }
}
