<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('department')->orderBy('name')->paginate(20);
        return view('catalogs.positions.index', compact('positions'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('catalogs.positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:positions,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'level' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        Position::create($data);
        return redirect()->route('positions.index')->with('success', 'Puesto creado.');
    }

    public function edit(Position $position)
    {
        $departments = Department::orderBy('name')->get();
        return view('catalogs.positions.edit', compact('position', 'departments'));
    }

    public function show(Position $position)
    {
        return redirect()->route('positions.edit', $position);
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:positions,code,' . $position->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'level' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        $position->update($data);
        return redirect()->route('positions.index')->with('success', 'Puesto actualizado.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Puesto eliminado.');
    }
}
