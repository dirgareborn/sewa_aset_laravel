<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        // show top-level units grouped by department (tree available via service)
        $units = Unit::with('department', 'parent')->orderBy('name')->get();

        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $parents = Unit::orderBy('name')->get();

        return view('admin.units.create', compact('departments', 'parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'parent_id' => 'nullable|exists:units,id',
            'name' => 'required|string|max:191',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->has('is_active');
        Unit::create($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit created.');
    }

    public function edit(Unit $unit)
    {
        $departments = Department::orderBy('name')->get();
        $parents = Unit::where('id', '!=', $unit->id)->orderBy('name')->get();

        return view('admin.units.edit', compact('unit', 'departments', 'parents'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'parent_id' => 'nullable|exists:units,id',
            'name' => 'required|string|max:191',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->has('is_active');

        // prevent parent loop
        if (! is_null($data['parent_id']) && $data['parent_id'] == $unit->id) {
            return back()->withInput()->with('error', 'Parent cannot be self.');
        }

        $unit->update($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit updated.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->children()->count()) {
            return back()->with('error', 'Remove or reassign sub-units before deleting.');
        }
        $unit->delete();

        return redirect()->route('admin.units.index')->with('success', 'Unit deleted.');
    }
}
