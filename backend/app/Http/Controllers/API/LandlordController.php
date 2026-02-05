<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Landlord;

class LandlordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Landlord::all();
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
            'name' => 'required|string',
            'email' => 'required|email|unique:landlords',
            'phone' => 'nullable|string'
        ]);

        $landlord = Landlord::create($validated);
        return response()->json($landlord, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Landlord $landlord)
    {
        return $landlord;
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
    public function update(Request $request, Landlord $landlord)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:landlords,email,' . $landlord->id,
            'phone' => 'nullable|string'
        ]);

        $landlord->update($validated);
        return response()->json($landlord);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $landlord = Landlord::findOrFail($id);
        $landlord->delete();
        return response()->json(['message' => 'Landlord deleted']);
    }
}
