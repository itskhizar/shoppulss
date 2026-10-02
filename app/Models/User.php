<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Default shipping address.
     */
    public function defaultAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /**
     * User orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * User carts.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Active cart.
     */
    public function activeCart(): HasOne
    {
        return $this->hasOne(Cart::class)->where('status', 'active');
    }

    /**
     * User roles (manual pivot via model_has_roles).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
            ->wherePivot('model_type', self::class);
    }

    /**
     * Check if user has specific role(s).
     */
    public function hasRole(string|array ...$roles): bool
    {
        $flattened = collect($roles)->flatten();

        return $this->roles->pluck('name')->intersect($flattened)->isNotEmpty();
    }

    /**
     * Check if user is an admin (any admin-level role).
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(
            'super-admin',
            'admin',
            'Super Admin',
            'Admin',
            'Store Admin',
            'Catalog Manager',
            'Order Manager',
            'Support Agent'
        );
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin');
    }

    /**
     * Module access helpers for role-based navigation and security.
     */
    public function canManageCatalog(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin', 'Admin', 'admin', 'Store Admin', 'Catalog Manager');
    }

    public function canManageOrders(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin', 'Admin', 'admin', 'Store Admin', 'Order Manager', 'Support Agent');
    }

    public function canManageCustomers(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin', 'Admin', 'admin', 'Store Admin', 'Order Manager', 'Support Agent');
    }

    public function canManageStaff(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin', 'Admin', 'admin', 'Store Admin');
    }

    public function canManageSettings(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin', 'Admin', 'admin', 'Store Admin');
    }

    public function canManageReviews(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin', 'Admin', 'admin', 'Store Admin', 'Catalog Manager', 'Support Agent');
    }

    public function canViewReports(): bool
    {
        return $this->hasRole('super-admin', 'Super Admin', 'Admin', 'admin', 'Store Admin', 'Order Manager');
    }

    public function getRoleTitleAttribute(): string
    {
        return $this->roles->first()?->name ?? ($this->isAdmin() ? 'Administrator' : 'Customer');
    }

    /**
     * Scope active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
