<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define permissions for Field Operations (Technician)
        $techPermissions = [
            'register-growths',
            'register-milkings',
            'register-batch-movements',
            'register-teasings',
        ];

        // 2. Define permissions for Clinical & Reproductive (Veterinarian)
        $vetPermissions = [
            'manage-clinic-histories',
            'register-diagnostics',
            'apply-treatments',
            'define-sanitary-plans',
            'perform-revisions',
            'register-mortality',
            'register-services',
            'register-births',
            'register-aborts',
            'manage-genetic-material',
        ];

        // 3. Define permissions for Farm Administration & Settings (Manager)
        $managerPermissions = [
            'manage-livestock',
            'manage-batches-paddocks',
            'configure-settings',
            'view-metrics',
            'register-outcomes',
            'register-entry-causes',
        ];

        // 4. Define permissions for Security & IT (Admin)
        $adminPermissions = [
            'manage-users',
            'view-audit-logs',
        ];

        // Combine all permissions list to register them in DB
        $allPermissions = array_merge(
            $techPermissions,
            $vetPermissions,
            $managerPermissions,
            $adminPermissions
        );

        foreach ($allPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // 5. Create Roles and assign cumulative permissions

        // Technician gets tech permissions
        $roleTech = Role::firstOrCreate(['name' => 'Technician']);
        $roleTech->syncPermissions($techPermissions);

        // Veterinarian gets tech + vet permissions
        $roleVet = Role::firstOrCreate(['name' => 'Veterinarian']);
        $roleVet->syncPermissions(array_merge($techPermissions, $vetPermissions));

        // Manager gets tech + vet + manager permissions
        $roleManager = Role::firstOrCreate(['name' => 'Manager']);
        $roleManager->syncPermissions(array_merge($techPermissions, $vetPermissions, $managerPermissions));

        // Admin gets all permissions (super-admin bypass is also configured in AppServiceProvider)
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->syncPermissions($allPermissions);
    }
}
