<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->has('property_id')){
            return Unit::where('property_id', $request->property_id)->get();
        }
        return Unit::all();
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
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string',
            'rent' => 'required|numeric',
            'status' => 'sometimes|in:vacant,occupied',
            'size' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);
        return Unit::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit){ return $unit; }

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
   public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'property_id' => 'sometimes|exists:properties,id',
            'unit_number' => 'sometimes|string',
            'rent' => 'sometimes|numeric',
            'status' => 'sometimes|in:vacant,occupied',
            'size' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);
        $unit->update($validated);
        return response()->json($unit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit){ $unit->delete(); return response()->json(['message'=>'Deleted']); }
}
