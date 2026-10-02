<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'display_name' => 'Super Administrator', 'description' => 'Full access to everything'],
            ['name' => 'Store Admin', 'display_name' => 'Store Administrator', 'description' => 'Manage store settings and staff'],
            ['name' => 'Catalog Manager', 'display_name' => 'Catalog Manager', 'description' => 'Manage products and categories'],
            ['name' => 'Order Manager', 'display_name' => 'Order Manager', 'description' => 'Manage orders and shipments'],
            ['name' => 'Support Agent', 'display_name' => 'Support Agent', 'description' => 'Handle customer support tickets'],
            ['name' => 'Customer', 'display_name' => 'Customer', 'description' => 'Registered customer'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(['name' => $roleData['name']], $roleData);
        }

        $permissions = [
            ['name' => 'view-products', 'display_name' => 'View Products'],
            ['name' => 'create-products', 'display_name' => 'Create Products'],
            ['name' => 'edit-products', 'display_name' => 'Edit Products'],
            ['name' => 'delete-products', 'display_name' => 'Delete Products'],
            ['name' => 'view-orders', 'display_name' => 'View Orders'],
            ['name' => 'manage-orders', 'display_name' => 'Manage Orders'],
            ['name' => 'view-users', 'display_name' => 'View Users'],
            ['name' => 'manage-users', 'display_name' => 'Manage Users'],
            ['name' => 'manage-settings', 'display_name' => 'Manage Settings'],
            ['name' => 'view-reports', 'display_name' => 'View Reports'],
        ];

        foreach ($permissions as $permData) {
            Permission::firstOrCreate(['name' => $permData['name']], $permData);
        }
    }
}
