<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->orderBy('name')->paginate(20);
        return view('catalogs.departments.index', compact('departments'));
    }

    public function create()
    {
        $managers = \App\Models\Employee::where('status', 'active')->orderBy('first_name')->get();
        $parents = Department::orderBy('name')->get();
        return view('catalogs.departments.create', compact('managers', 'parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:departments,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'cost_center' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        Department::create($data);
        return redirect()->route('departments.index')->with('success', 'Departamento creado.');
    }

    public function edit(Department $department)
    {
        $managers = \App\Models\Employee::where('status', 'active')->orderBy('first_name')->get();
        $parents = Department::where('id', '!=', $department->id)->orderBy('name')->get();
        return view('catalogs.departments.edit', compact('department', 'managers', 'parents'));
    }

    public function show(Department $department)
    {
        return redirect()->route('departments.edit', $department);
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'cost_center' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        $department->update($data);
        return redirect()->route('departments.index')->with('success', 'Departamento actualizado.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Departamento eliminado.');
    }
}
