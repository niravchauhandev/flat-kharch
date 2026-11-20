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
        if (!Schema::hasTable('monthly_pools')) {
            Schema::create('monthly_pools', function (Blueprint $table) {
                $table->id();
                $table->string('pool_code', 50)->unique()->comment('Unique monthly pool cycle reference code');
                $table->unsignedTinyInteger('target_month')->comment('Target month from 1 to 12');
                $table->unsignedSmallInteger('target_year')->comment('Target calendar year e.g. 2026');
                $table->decimal('target_amount_per_member', 10, 2)->default(0.00)->comment('Target contribution amount per flatmate');
                $table->decimal('total_target_amount', 10, 2)->default(0.00)->comment('Total calculated pool target collection');
                $table->decimal('opening_balance', 10, 2)->default(0.00)->comment('Surplus fund rolled over from previous month');
                $table->text('notes')->nullable()->comment('General remarks or cycle instructions');
                $table->unsignedTinyInteger('pool_status')->default(1)->comment('1: Ongoing, 2: Settled and Closed');
                $table->unsignedTinyInteger('status')->default(1)->comment('1: Active, 2: Inactive, 3: Deleted');
                $table->unsignedBigInteger('added_by')->nullable()->comment('User ID who initialized this pool');
                $table->unsignedBigInteger('updated_by')->nullable()->comment('User ID who last updated this pool');
                $table->timestamps();

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
        Schema::dropIfExists('monthly_pools');
    }
};
