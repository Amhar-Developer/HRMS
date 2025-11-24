<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin Role (if not exists)
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // Create the main admin user
        $user = User::updateOrCreate(
            ['email' => 'superadmin@hrms.com'],
            [
                'name' => 'Super Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), 
            ]
        );

        // Assign Super Admin Role
        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }
    }
}
