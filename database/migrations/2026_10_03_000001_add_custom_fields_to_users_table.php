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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->tinyInteger('role')->default(2)->comment('1: Admin, 2: Member')->after('phone');
            $table->string('avatar')->nullable()->after('role');
            $table->tinyInteger('is_active')->default(1)->comment('1: Active, 2: Inactive')->after('avatar');
            $table->tinyInteger('status')->default(1)->comment('1: Active, 2: Inactive')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role',
                'avatar',
                'is_active',
                'status',
            ]);
        });
    }
};
