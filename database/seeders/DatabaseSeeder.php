<?php

namespace Database\Seeders;

use App\Http\Controllers\CallButtonSettingController;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run Roles and Permissions Seeder
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CallButtonSettingsSeeder::class);

        // Create a test admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Default password: "password"
            ]
        );

        // Assign all permissions to admin user
        $adminUser->syncPermissions([
            'full_permissions',
            'clear_tables',
            'handle_requests',
            'manage_login_pages',
            'configure_buttons',
            'manage_tables',
            'call_screen',
            'manage_reports',
        ]);

        // Display user credentials in console
        $this->command->info('Admin User created with full permissions:');
        $this->command->info('Email: admin@example.com | Password: password');
    }
}
