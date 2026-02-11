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
            $table->foreignId('fine_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('received_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->decimal('amount', 8, 2);
            $table->enum('payment_method', ['cash', 'check', 'money_order', 'bank_transfer', 'other'])->default('cash');
            $table->string('check_number')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->timestamp('payment_date');
            $table->text('notes')->nullable();
            $table->string('receipt_number')->unique();
            $table->string('receipt_path')->nullable()->comment('PDF receipt file path');
            $table->timestamps();

            $table->index(['member_id', 'payment_date']);
            $table->index(['fine_id']);
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
