<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->paginate(20);
        return view('catalogs.locations.index', compact('locations'));
    }

    public function create()
    {
        $parents = Location::orderBy('name')->get();
        return view('catalogs.locations.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:locations,code',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:locations,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        Location::create($data);
        return redirect()->route('locations.index')->with('success', 'Ubicación creada.');
    }

    public function show(Location $location)
    {
        return redirect()->route('locations.edit', $location);
    }

    public function edit(Location $location)
    {
        $parents = Location::where('id', '!=', $location->id)->orderBy('name')->get();
        return view('catalogs.locations.edit', compact('location', 'parents'));
    }

    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:locations,code,' . $location->id,
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:locations,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        $location->update($data);
        return redirect()->route('locations.index')->with('success', 'Ubicación actualizada.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Ubicación eliminada.');
    }
}
