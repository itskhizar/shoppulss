<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            SettingsSeeder::class,
            ProductSeeder::class,
            CourierSeeder::class,
            PageSeeder::class,
        ]);

        // Create or get super admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@shoppulss.com'],
            [
                'name' => 'ShopPulss Admin',
                'password' => Hash::make('password'),
                'phone' => '+923001234567',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole && ! $admin->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $admin->roles()->attach($superAdminRole->id, ['model_type' => User::class]);
        }

        // Create sample customer if none exists
        if (User::where('email', 'customer@shoppulss.com')->doesntExist()) {
            $customer = User::create([
                'name' => 'Demo Customer',
                'email' => 'customer@shoppulss.com',
                'password' => Hash::make('password'),
                'phone' => '+923007654321',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            $customerRole = Role::where('name', 'Customer')->first();
            if ($customerRole) {
                $customer->roles()->attach($customerRole->id, ['model_type' => User::class]);
            }
        }
    }
}
