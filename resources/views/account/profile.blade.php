@extends('layouts.app')

@section('title', 'Profile & Security - ShopPulss')

@section('content')
<div class="bg-white border-b border-[#E6E8F2] py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="breadcrumb mb-2">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('account.dashboard') }}">Account</a>
            <span>/</span>
            <span class="current">Profile & Security</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] tracking-tight">
            Profile & Security
        </h1>
        <p class="text-xs text-gray-500 mt-1">Manage your direct retail contact details and update your password</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        <div class="lg:col-span-3 space-y-6">
            {{-- Edit Profile Form --}}
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-7 shadow-sp-card">
                <div class="pb-4 border-b border-[#E6E8F2] mb-5">
                    <h2 class="text-base font-black text-[#0F1654]">Personal Information</h2>
                    <p class="text-xs text-gray-400">Used for courier communication and order notifications</p>
                </div>

                <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4 max-w-lg">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="sp-label">Full Name</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="sp-input"
                        >
                    </div>

                    <div>
                        <label for="email" class="sp-label">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="sp-input"
                        >
                    </div>

                    <div>
                        <label for="phone" class="sp-label">Phone Number</label>
                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="sp-input font-mono"
                            placeholder="+92 300 1234567"
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        <span>Save Profile Changes</span>
                    </button>
                </form>
            </div>

            {{-- Update Password Form --}}
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-7 shadow-sp-card">
                <div class="pb-4 border-b border-[#E6E8F2] mb-5">
                    <h2 class="text-base font-black text-[#0F1654]">Change Password</h2>
                    <p class="text-xs text-gray-400">Ensure your account remains safe with a strong password</p>
                </div>

                <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4 max-w-lg">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="sp-label">Current Password</label>
                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            required
                            class="sp-input"
                        >
                    </div>

                    <div>
                        <label for="password" class="sp-label">New Password (min 8 characters)</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="sp-input"
                            placeholder="••••••••"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="sp-label">Confirm New Password</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            class="sp-input"
                            placeholder="••••••••"
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn-navy"
                    >
                        <span>Update Password</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
