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
        $user = $request->user();

        if ($user->role === 'admin') {
            return Payment::with(['tenant', 'unit'])->latest()->get();
        }

        if ($user->role === 'tenant') {
            return Payment::with('unit')
                ->where('tenant_id', $user->tenant->id)
                ->latest()
                ->get();
        }

         // landlord → only payments of their tenants
        return Payment::with(['tenant', 'unit'])
            ->whereHas('unit.property', function ($q) use ($user) {
                $q->where('landlord_id', $user->landlord_id);
            })->latest()->get();
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
            'unit_id' => 'required|exists:units,id',
            'amount_due' => 'required|numeric',
            'due_date' => 'required|date',
        ]);

        $validated['amount_paid'] = 0;
        $validated['status'] = 'unpaid';

        return response()->json(Payment::create($validated), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['tenant', 'unit']);
        $payment->balance = $payment->amount_due - $payment->amount_paid;
        $payment->is_overdue = $payment->status !== 'paid' && now()->gt($payment->due_date);

        return response()->json($payment);
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
            'amount_paid' => 'required|numeric|min:0'
        ]);

        // Add payment
        $payment->amount_paid += $validated['amount_paid'];

        // Auto update status
        if ($payment->amount_paid >= $payment->amount_due) {
            $payment->status = 'paid';
        } elseif ($payment->amount_paid > 0) {
            $payment->status = 'partial';
        } else {
            $payment->status = 'unpaid';
        }

        $payment->save();

        $payment->balance = $payment->amount_due - $payment->amount_paid;
        $payment->is_overdue = $payment->status !== 'paid' && now()->gt($payment->due_date);

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
