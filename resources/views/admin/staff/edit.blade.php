@extends('layouts.admin')

@section('title', 'Edit Staff Member - ' . $staffMember->name)
@section('header', 'Edit Staff Member')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.staff.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 flex items-center gap-1">
            ← Back to Staff List
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2" style="color: #0F1B4D;">Edit Staff Account</h1>
        <p class="text-xs text-gray-500">Update staff role, contact details, or credentials</p>
    </div>

    <form method="POST" action="{{ route('admin.staff.update', $staffMember->id) }}" class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $staffMember->name) }}" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
            @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address *</label>
            <input type="email" name="email" value="{{ old('email', $staffMember->email) }}" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
            @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone', $staffMember->phone) }}" placeholder="+923001234567" class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
            @error('phone') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Assigned Role *</label>
                <select name="role_id" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ (old('role_id', $staffMember->roles->first()?->id) == $role->id) ? 'selected' : '' }}>
                            {{ $role->name }} - {{ $role->description }}
                        </option>
                    @endforeach
                </select>
                @error('role_id') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Account Status *</label>
                <select name="status" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
                    <option value="active" {{ old('status', $staffMember->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $staffMember->status) === 'inactive' ? 'selected' : '' }}>Inactive / Suspended</option>
                </select>
                @error('status') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-2 border-t border-gray-100">
            <h3 class="text-xs font-bold text-gray-700 mb-1">Reset Password (Leave blank to keep unchanged)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">New Password</label>
                    <input type="password" name="password" class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
                    @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2 rounded-xl text-white text-xs font-bold shadow-sm hover:opacity-95" style="background-color: #00A8B8;">
                Update Staff Member
            </button>
        </div>
    </form>
</div>
@endsection
