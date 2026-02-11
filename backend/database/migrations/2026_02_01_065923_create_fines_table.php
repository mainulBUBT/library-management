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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('copy_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('fine_type', ['late_return', 'damage', 'lost', 'other'])->default('late_return');
            $table->decimal('amount', 8, 2);
            $table->decimal('paid_amount', 8, 2)->default(0);
            $table->enum('status', ['pending', 'partially_paid', 'paid', 'waived'])->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamp('calculated_at');
            $table->text('description')->nullable();
            $table->text('waiver_reason')->nullable();
            $table->foreignId('waived_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->timestamp('waived_at')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
