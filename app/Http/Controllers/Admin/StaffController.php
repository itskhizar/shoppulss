<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * List all staff members with filtering.
     */
    public function index(Request $request): View
    {
        $this->checkStaffAccess();

        $query = User::whereHas('roles', function ($q) {
            $q->whereIn('name', [
                'super-admin', 'Super Admin', 'admin', 'Admin',
                'Store Admin', 'Catalog Manager', 'Order Manager', 'Support Agent',
            ]);
        })->with('roles');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleName = $request->get('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $roleName));
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $staff = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::whereIn('name', ['Super Admin', 'Store Admin', 'Catalog Manager', 'Order Manager', 'Support Agent'])->get();

        return view('admin.staff.index', compact('staff', 'roles'));
    }

    /**
     * Show form to create staff member.
     */
    public function create(): View
    {
        $this->checkStaffAccess();

        $roles = Role::whereIn('name', ['Super Admin', 'Store Admin', 'Catalog Manager', 'Order Manager', 'Support Agent'])->get();

        return view('admin.staff.create', compact('roles'));
    }

    /**
     * Store new staff member.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkStaffAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $user->roles()->attach($validated['role_id'], ['model_type' => User::class]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created successfully!');
    }

    public function __construct()
    {
        // Require Super Admin or Store Admin to manage staff accounts
    }

    protected function checkStaffAccess(): void
    {
        if (! auth()->user()?->hasRole('Super Admin', 'super-admin', 'Store Admin', 'Admin', 'admin')) {
            abort(403, 'Unauthorized. Super Admin or Store Admin privileges required.');
        }
    }

    /**
     * Remove staff member.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkStaffAccess();
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff account deleted.');
    }

    /**
     * Show staff member profile.
     */
    public function show(int $id): View
    {
        $this->checkStaffAccess();
        $staffMember = User::with('roles')->findOrFail($id);

        return view('admin.staff.show', compact('staffMember'));
    }

    /**
     * Edit staff member.
     */
    public function edit(int $id): View
    {
        $this->checkStaffAccess();
        $staffMember = User::with('roles')->findOrFail($id);
        $roles = Role::whereIn('name', ['Super Admin', 'Store Admin', 'Catalog Manager', 'Order Manager', 'Support Agent'])->get();

        return view('admin.staff.edit', compact('staffMember', 'roles'));
    }

    /**
     * Update staff member.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkStaffAccess();
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        if (! empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->roles()->sync([$validated['role_id'] => ['model_type' => User::class]]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account updated successfully!');
    }
}
