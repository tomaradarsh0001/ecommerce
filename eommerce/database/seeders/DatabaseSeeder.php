<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = Role::create(['name' => 'admin']);
    $user = Role::create(['name' => 'user']);

    // Create permissions
    Permission::create(['name' => 'view dashboard']);
    Permission::create(['name' => 'edit articles']);

    // Assign permissions to roles
    $admin->givePermissionTo(['view dashboard', 'edit articles']);
    $user->givePermissionTo(['view dashboard']);
    }
}
