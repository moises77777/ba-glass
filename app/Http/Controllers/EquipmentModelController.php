<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\EquipmentCategory;
use App\Models\EquipmentModel;
use Illuminate\Http\Request;

class EquipmentModelController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentModel::with('brand', 'category');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$q}%"));
            });
        }

        $models = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('equipment-models.index', compact('models'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = EquipmentCategory::whereIn('code', ['LAPTOP', 'DESKTOP'])->orderBy('sort_order')->get();
        return view('equipment-models.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id'        => 'nullable|exists:brands,id',
            'category_id'     => 'nullable|exists:equipment_categories,id',
            'name'            => 'required|string|max:150',
            'part_number'     => 'nullable|string|max:100',
            'processor'       => 'nullable|string|max:150',
            'ram'             => 'nullable|string|max:50',
            'storage'         => 'nullable|string|max:50',
            'storage_type'    => 'nullable|string|max:50',
            'graphics_card'   => 'nullable|string|max:150',
            'screen_size'     => 'nullable|string|max:50',
            'operating_system'=> 'nullable|string|max:100',
            'reference_price' => 'nullable|numeric|min:0',
            'currency'        => 'nullable|string|max:3',
            'notes'           => 'nullable|string',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        EquipmentModel::create($validated);

        return redirect()->route('equipment-models.index')
            ->with('success', 'Modelo creado exitosamente.');
    }

    public function edit(EquipmentModel $equipmentModel)
    {
        $brands = Brand::orderBy('name')->get();
        $categories = EquipmentCategory::whereIn('code', ['LAPTOP', 'DESKTOP'])->orderBy('sort_order')->get();
        return view('equipment-models.edit', compact('equipmentModel', 'brands', 'categories'));
    }

    public function update(Request $request, EquipmentModel $equipmentModel)
    {
        $validated = $request->validate([
            'brand_id'        => 'nullable|exists:brands,id',
            'category_id'     => 'nullable|exists:equipment_categories,id',
            'name'            => 'required|string|max:150',
            'part_number'     => 'nullable|string|max:100',
            'processor'       => 'nullable|string|max:150',
            'ram'             => 'nullable|string|max:50',
            'storage'         => 'nullable|string|max:50',
            'storage_type'    => 'nullable|string|max:50',
            'graphics_card'   => 'nullable|string|max:150',
            'screen_size'     => 'nullable|string|max:50',
            'operating_system'=> 'nullable|string|max:100',
            'reference_price' => 'nullable|numeric|min:0',
            'currency'        => 'nullable|string|max:3',
            'notes'           => 'nullable|string',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $equipmentModel->update($validated);

        return redirect()->route('equipment-models.index')
            ->with('success', 'Modelo actualizado exitosamente.');
    }

    public function destroy(EquipmentModel $equipmentModel)
    {
        $equipmentModel->delete();
        return redirect()->route('equipment-models.index')
            ->with('success', 'Modelo eliminado.');
    }

    public function apiGet(EquipmentModel $equipmentModel)
    {
        return response()->json([
            'brand_id'         => $equipmentModel->brand_id,
            'category_id'      => $equipmentModel->category_id,
            'model'            => $equipmentModel->name,
            'part_number'      => $equipmentModel->part_number,
            'processor'        => $equipmentModel->processor,
            'ram'              => $equipmentModel->ram,
            'storage'          => $equipmentModel->storage,
            'storage_type'     => $equipmentModel->storage_type,
            'graphics_card'    => $equipmentModel->graphics_card,
            'screen_size'      => $equipmentModel->screen_size,
            'operating_system' => $equipmentModel->operating_system,
            'purchase_price'   => $equipmentModel->reference_price,
            'currency'         => $equipmentModel->currency,
        ]);
    }
}
