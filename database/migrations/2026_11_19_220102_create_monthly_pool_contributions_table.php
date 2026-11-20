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
        if (!Schema::hasTable('monthly_pool_contributions')) {
            Schema::create('monthly_pool_contributions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('monthly_pool_id')->comment('Referenced monthly pool ID');
                $table->unsignedBigInteger('user_id')->comment('Referenced flatmate user ID');
                $table->decimal('expected_amount', 10, 2)->default(0.00)->comment('Assigned pool share for this flatmate');
                $table->decimal('paid_amount', 10, 2)->default(0.00)->comment('Actual amount deposited by this flatmate');
                $table->unsignedTinyInteger('payment_status')->default(2)->comment('1: Paid, 2: Pending, 3: Partial');
                $table->unsignedTinyInteger('payment_mode')->nullable()->comment('1: Cash, 2: UPI / GPay, 3: Bank Transfer, 4: Other');
                $table->string('transaction_reference', 100)->nullable()->comment('Bank or UPI transaction reference');
                $table->date('payment_date')->nullable()->comment('Date on which payment was verified');
                $table->text('notes')->nullable()->comment('Optional contribution notes');
                $table->unsignedTinyInteger('status')->default(1)->comment('1: Active, 2: Inactive, 3: Deleted');
                $table->unsignedBigInteger('added_by')->nullable()->comment('User ID who recorded this entry');
                $table->unsignedBigInteger('updated_by')->nullable()->comment('User ID who last modified this entry');
                $table->timestamps();

                $table->foreign('monthly_pool_id')->references('id')->on('monthly_pools')->cascadeOnDelete();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->foreign('added_by')->references('id')->on('users')->nullOnDelete();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_pool_contributions');
    }
};
