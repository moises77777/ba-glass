<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->paginate(20);
        return view('catalogs.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('catalogs.brands.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'support_phone' => 'nullable|string|max:50',
            'support_email' => 'nullable|email',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        Brand::create($data);
        return redirect()->route('brands.index')->with('success', 'Marca creada.');
    }

    public function show(Brand $brand)
    {
        return redirect()->route('brands.edit', $brand);
    }

    public function edit(Brand $brand)
    {
        return view('catalogs.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'support_phone' => 'nullable|string|max:50',
            'support_email' => 'nullable|email',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        $brand->update($data);
        return redirect()->route('brands.index')->with('success', 'Marca actualizada.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Marca eliminada.');
    }
}
