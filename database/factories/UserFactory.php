<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+92'.fake()->randomElement(['300', '301', '302', '312', '321', '333', '345']).fake()->numerify('#######'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign super-admin role.
     */
    public function superAdmin(): static
    {
        return $this->afterCreating(function (User $user) {
            $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
            $user->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);
        });
    }

    /**
     * Assign customer role.
     */
    public function customer(): static
    {
        return $this->afterCreating(function (User $user) {
            $role = Role::firstOrCreate(['name' => 'Customer'], ['display_name' => 'Customer']);
            $user->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);
        });
    }
}
