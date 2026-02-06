<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return Property::all();
        }

        return Property::where('landlord_id', $user->landlord_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->validate([
        'name' => 'required|string',
        'address' => 'required|string'
        ]);

        $data['landlord_id'] = auth()->user()->landlord_id;
        
        return Property::create($data); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $property->landlord_id !== $user->landlord_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $property;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'landlord_id' => 'sometimes|exists:landlords,id',
            'name' => 'sometimes|string',
            'address' => 'sometimes|string',
        ]);

        $property->update($validated);
        return response()->json($property);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();
        return response()->json(['message' => 'Property deleted']);
    }
}
