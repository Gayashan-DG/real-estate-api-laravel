<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $perPage = min($perPage, 100);

        $query = Property::query();

        $filters = [
            'city' => 'like',
            'country' => 'like',
            'property_type' => '=',
            'status' => '=',
            'bedrooms' => '=',
            'bathrooms' => '=',
        ];

        foreach ($filters as $field => $operator) {
            if ($request->has($field)) {
                $value = $request->input($field);

                if ($operator === 'like') {
                    $query->where($field, 'like', '%' . $value . '%');
                } else {
                    $query->where($field, $operator, $value);
                }
            }
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $allowedSorts = ['price', 'created_at', 'bedrooms', 'bathrooms'];
        $sortField = $request->query('sort', 'created_at'); // Default: newest first

        $sortDirection = 'asc';
        if (str_starts_with($sortField, '-')) {
            $sortDirection = 'desc';
            $sortField = substr($sortField, 1); // Remove the - prefix
        }

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $properties = $query->paginate($perPage);
        return response()->json($properties);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'property_type' => 'required|in:house,apartment,land,commercial',
            'address' => 'required|string',
            'city' => 'required|string',
            'province_state' => 'nullable|string',
            'country' => 'required|string',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_sqft' => 'nullable|numeric|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:available,sold,rented',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('properties', 'public');
            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);

        $validated['user_id'] = $request->user()->id;

        $property = Property::create($validated);

        if ($property->image_path) {
            $property->image_url = asset('storage/' . $property->image_path);
        }

        return response()->json($property, 201);
    }


    public function show(string $id)
    {
        $property = Property::findOrFail($id);
        return response()->json($property);
    }

    public function update(Request $request, string $id)
    {
        $property = Property::findOrFail($id);

        if ($property->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Forbidden. You can only update your own properties.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'property_type' => 'sometimes|in:house,apartment,land,commercial',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'province_state' => 'nullable|string',
            'country' => 'sometimes|string',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_sqft' => 'nullable|numeric|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:available,sold,rented',
        ]);

        if ($request->hasFile('image')) {
            if ($property->image_path) {
                Storage::disk('public')->delete($property->image_path);
            }

            $imagePath = $request->file('image')->store('properties', 'public');
            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);

        $property->update($validated);

        if ($property->image_path) {
            $property->image_url = asset('storage/' . $property->image_path);
        }

        return response()->json($property);
    }


    public function destroy(Request $request, string $id)
    {
        $property = Property::findOrFail($id);

        if ($property->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Forbidden. You can only delete your own properties.'
            ], 403);
        }

        // Delete image if exists
        if ($property->image_path) {
            Storage::disk('public')->delete($property->image_path);
        }

        $property->delete();

        return response()->json(null, 204);
    }
}
