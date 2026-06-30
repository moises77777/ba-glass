<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use Illuminate\Http\Request;

class EquipmentCategoryController extends Controller
{
    public function index()
    {
        $categories = EquipmentCategory::orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('catalogs.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = EquipmentCategory::whereNull('parent_id')->orderBy('name')->get();
        return view('catalogs.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:equipment_categories,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:equipment_categories,id',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'requires_serial' => 'boolean',
            'is_active' => 'boolean',
        ]);
        $data['requires_serial'] = $request->has('requires_serial');
        $data['is_active'] = $request->has('is_active');
        EquipmentCategory::create($data);
        return redirect()->route('categories.index')->with('success', 'Categoría creada.');
    }

    public function show(EquipmentCategory $category)
    {
        return redirect()->route('categories.edit', $category);
    }

    public function edit(EquipmentCategory $category)
    {
        $parents = EquipmentCategory::where('id', '!=', $category->id)->whereNull('parent_id')->orderBy('name')->get();
        return view('catalogs.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, EquipmentCategory $category)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:equipment_categories,code,' . $category->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:equipment_categories,id',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'requires_serial' => 'boolean',
            'is_active' => 'boolean',
        ]);
        $data['requires_serial'] = $request->has('requires_serial');
        $data['is_active'] = $request->has('is_active');
        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(EquipmentCategory $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada.');
    }
}
