<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            LookUpTablesSeeder::class,
            PeopleSeeder::class,
            ClinicalSeeder::class,
            InventorySeeder::class,
            LivestockSeeder::class,
            InventoryMovementSeeder::class,
            LivestockEventSeeder::class,
            MovementKardexSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@llanos.com',
        ]);
        $admin->assignRole('Admin');
    }
}
