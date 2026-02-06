<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return Payment::all();
        }

        if ($user->role === 'tenant') {
            return Payment::whereHas('tenant', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();
        }

        // landlord → only payments of their tenants
        return Payment::whereHas('tenant.unit.property', function ($q) use ($user) {
            $q->where('landlord_id', $user->landlord_id);
        })->get();
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
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'status' => 'sometimes|in:paid,pending',
        ]);

        return response()->json(Payment::create($validated), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return $payment->load('tenant');
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
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'tenant_id' => 'sometimes|exists:tenants,id',
            'amount' => 'sometimes|numeric',
            'payment_date' => 'sometimes|date',
            'status' => 'sometimes|in:paid,pending',
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Payment deleted']);
    }
}
