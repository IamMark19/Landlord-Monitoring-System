<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Payment;
use Carbon\Carbon;

class GenerateMonthlyPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly payments for all tenants starting from rent_start_date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::with('unit')->get();
        $today = Carbon::now();

        foreach ($tenants as $tenant) {

            if (!$tenant->rent_start_date || !$tenant->unit) continue;

            $start = Carbon::parse($tenant->rent_start_date)->startOfMonth();
            $current = $start->copy();

            // Generate payments for all months from start until current month
            while ($current->lte($today->startOfMonth())) {

                $exists = Payment::where('tenant_id', $tenant->id)
                    ->whereMonth('due_date', $current->month)
                    ->whereYear('due_date', $current->year)
                    ->exists();

                if (!$exists) {
                    Payment::create([
                        'tenant_id' => $tenant->id,
                        'unit_id' => $tenant->unit->id,
                        'amount_due' => $tenant->unit->rent,
                        'amount_paid' => 0,
                        'due_date' => $current->copy(),
                        'status' => 'unpaid',
                    ]);
                }

                $current->addMonth();
            }
        }
        
        $this->info('Monthly payments generated successfully!');
    }
    
}
