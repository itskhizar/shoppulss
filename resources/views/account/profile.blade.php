@extends('layouts.app')

@section('title', 'Profile & Security - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <h1 class="text-2xl font-black text-gray-900" style="color: #0F1B4D;">
            Profile & Security
        </h1>
        <p class="text-xs text-gray-500 mt-0.5">Manage your contact details and update your password</p>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        <div class="lg:col-span-3 space-y-6">
            {{-- Edit Profile Form --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4" style="color: #0F1B4D;">Personal Information</h2>

                <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4 max-w-lg">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Full Name</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                        >
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1">Phone Number</label>
                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                            placeholder="+92 300 1234567"
                        >
                    </div>

                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-lg text-white font-semibold text-xs shadow-sm hover:opacity-95"
                        style="background-color: #0F1B4D;"
                    >
                        Save Changes
                    </button>
                </form>
            </div>

            {{-- Update Password Form --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4" style="color: #0F1B4D;">Change Password</h2>

                <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4 max-w-lg">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-gray-700 mb-1">Current Password</label>
                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            required
                            class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">New Password (min 8 characters)</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                        >
                    </div>

                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-lg text-white font-semibold text-xs shadow-sm hover:opacity-95"
                        style="background-color: #00A8B8;"
                    >
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
