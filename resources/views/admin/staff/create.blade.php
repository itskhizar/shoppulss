@extends('layouts.admin')

@section('title', 'Add Staff Member')
@section('header', 'Add Staff Member')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.staff.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 flex items-center gap-1">
            ← Back to Staff List
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2" style="color: #0F1B4D;">Create Staff Account</h1>
        <p class="text-xs text-gray-500">Provide user credentials and assign an administrative role</p>
    </div>

    <form method="POST" action="{{ route('admin.staff.store') }}" class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
            @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address *</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
            @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+923001234567" class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
            @error('phone') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Role *</label>
            <select name="role_id" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
                <option value="">Select a role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }} - {{ $role->description }}
                    </option>
                @endforeach
            </select>
            @error('role_id') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Password *</label>
                <input type="password" name="password" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
                @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm Password *</label>
                <input type="password" name="password_confirmation" required class="w-full px-3.5 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-teal-500">
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2 bg-[#00A8B8] hover:bg-[#008F9C] text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                Create Staff Account
            </button>
        </div>
    </form>
</div>
@endsection
