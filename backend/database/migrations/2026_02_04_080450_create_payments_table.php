<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
           $table->foreignId('tenant_id')->constrained(); 
           $table->foreignId('unit_id')->constrained();
            $table->decimal('amount_due', 10, 2);
           $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('due_date');
             $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
