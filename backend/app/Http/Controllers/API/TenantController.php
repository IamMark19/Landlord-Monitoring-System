<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('unit_id')) {
            return Tenant::where('unit_id', $request->unit_id)->get();
        }

        return Tenant::all();
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
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'nullable|string',
        ]);

        return response()->json(Tenant::create($validated), 201);
    }

    /**
     * Display the specified resource.
     */
     public function show(Tenant $tenant)
    {
        return $tenant->load('unit', 'payments', 'maintenanceRequests');
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
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'unit_id' => 'sometimes|exists:units,id',
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:tenants,email,' . $tenant->id,
            'phone' => 'nullable|string',
        ]);

        $tenant->update($validated);
        return response()->json($tenant);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return response()->json(['message' => 'Tenant deleted']);
    }
}
