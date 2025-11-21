<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }

    public function create(Request $request, $id = null)
    {

        $categories = Category::all();
        $employee = Employee::where('id', $id)->first();
        $title = 'Tambah Pegawai';

        return view('admin.employees.form', compact('employee', 'title','categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'email' => 'required|email|unique:employees,email',
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sosmed' => 'nullable',
            'status' => 'boolean',
            'position'=>'nullable|string|max:255',
            'categories'=>'nullable|array'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/employees', 'public');
        }

        $data['sosmed'] = $data['sosmed'] ?? null;

        // Employee::create($data);
        $employee = Employee::create($request->only(['employee_id','name','email','position']));
        if($request->categories){
            $employee->categories()->attach($request->categories);
        }
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $title = 'Edit Data Pegawai';
        // $categories = Category::all();
        $categories = Category::with('children')->get(); // atau filter sesuai departemen
        // dd($categories);
        return view('admin.employees.form', compact('employee', 'title','categories'));
    }

    public function update(Request $request, Employee $employee)
    {
        dd($request->all());
        $request->merge([
        'sosmed' => $request->input('sosmed') ? json_decode($request->input('sosmed'), true) : [],
        ]);

        $data = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,'.$employee->id,
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sosmed' => 'nullable|array',
            'status' => 'boolean',
            'position'=>'nullable|string|max:255',
            'categories'=>'nullable|array'
        ]);

        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $data['image'] = $request->file('image')->store('uploads/employees', 'public');
        }

        $data['sosmed'] = $data['sosmed'] ?? null;

        // $employee->update($data);
        $employee->update($request->only(['employee_id','name','email','position']));
        $employee->categories()->sync($request->categories ?? []);
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
