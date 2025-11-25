<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('units')->paginate(20);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $units = Unit::with('department')->get();

        return view('admin.employees.create', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'position' => 'nullable|string',
            'role' => 'required|in:office,unit',
            'is_global_staff' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'sosmed' => 'nullable|array',
            'status' => 'boolean',
            'units' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/employees', 'public');
        }

        $employee = Employee::create($data);

        if (! empty($data['units']) && $employee->role === 'unit') {
            $employee->units()->sync($data['units']);
        }

        return redirect()->route('admin.employees.index')->with('success', 'Employee created.');
    }

    public function edit(Employee $employee)
    {
        $units = Unit::with('department')->get();
        $employee->load('units');

        return view('admin.employees.edit', compact('employee', 'units'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,'.$employee->id,
            'name' => 'required|string',
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'position' => 'nullable|string',
            'role' => 'required|in:office,unit',
            'is_global_staff' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'sosmed' => 'nullable|array',
            'status' => 'boolean',
            'units' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $data['image'] = $request->file('image')->store('uploads/employees', 'public');
        }

        $employee->update($data);

        // sync only if role is unit; otherwise detach units
        if ($data['role'] === 'unit') {
            $employee->units()->sync($data['units'] ?? []);
        } else {
            $employee->units()->detach();
        }

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted.');
    }
}
