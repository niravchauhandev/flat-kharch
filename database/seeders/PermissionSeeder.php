<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds for Roles and Permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Execute SQL file if exists
        $sqlPath = database_path('sql/permission_data.sql');
        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            DB::unprepared($sql);
        }

        // 2. Create or verify Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $memberRole = Role::firstOrCreate(['name' => 'Member', 'guard_name' => 'web']);

        // 3. Admin gets ALL permissions
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);

        // 4. Member gets member-specific permissions
        $memberPermissions = [
            'dashboard view',
            'expense list',
            'expense add',
            'monthly-pool list',
            'cash-advance list',
            'grocery list',
            'grocery add',
            'leave list',
            'leave add',
            'settlement list',
            'report list',
        ];

        $validMemberPermissions = Permission::whereIn('name', $memberPermissions)->get();
        $memberRole->syncPermissions($validMemberPermissions);

        // 5. Assign roles to users based on user->role column
        $adminUsers = User::where('role', 1)->get();
        foreach ($adminUsers as $admin) {
            if (!$admin->hasRole('Admin')) {
                $admin->assignRole($adminRole);
            }
        }

        $memberUsers = User::where('role', 2)->get();
        foreach ($memberUsers as $member) {
            if (!$member->hasRole('Member')) {
                $member->assignRole($memberRole);
            }
        }
    }
}
