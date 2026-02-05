<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        if ($request->has('tenant_id')) {
            return MaintenanceRequest::where('tenant_id', $request->tenant_id)->get();
        }

        if ($request->has('unit_id')) {
            return MaintenanceRequest::where('unit_id', $request->unit_id)->get();
        }

        return MaintenanceRequest::all();
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
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'nullable|exists:units,id',
            'description' => 'required|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'priority' => 'sometimes|in:low,medium,high',
        ]);

        return response()->json(MaintenanceRequest::create($validated), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceRequest $maintenanceRequest)
    {
        return $maintenanceRequest->load('tenant', 'unit');
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
    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'tenant_id' => 'sometimes|exists:tenants,id',
            'unit_id' => 'nullable|exists:units,id',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'priority' => 'sometimes|in:low,medium,high',
        ]);

        $maintenanceRequest->update($validated);
        return response()->json($maintenanceRequest);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();
        return response()->json(['message' => 'Maintenance request deleted']);
    }
}
