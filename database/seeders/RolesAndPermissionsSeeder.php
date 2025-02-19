<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database with predefined permissions.
     */
    public function run()
    {
        // Clear cached permissions to avoid conflicts
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define the permissions based on the provided UI
        $permissions = [
            'full_permissions',
            'clear_tables',
            'handle_requests',
            'manage_login_pages',
            'configure_buttons',
            'manage_tables',
            'call_screen',
            'manage_reports',
        ];

        // Create permissions if they do not exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to the default admin user for testing purposes
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->syncPermissions($permissions);
        }
    }
}
