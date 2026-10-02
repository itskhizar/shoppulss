@extends('layouts.app')

@section('title', 'Reset Password - ShopPulss')

@section('content')
<div class="min-h-[65vh] py-12 px-4 flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-1.5 justify-center mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-black" style="background-color: #00A8B8;">S</div>
                    <span class="text-2xl font-black tracking-tight" style="color: #0F1B4D;">Shop<span style="color: #00A8B8;">Pulss</span></span>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Set New Password</h1>
                <p class="text-xs text-gray-500 mt-1">Please enter your email and set a new password</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs font-medium text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        required
                        autofocus
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">New Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                        placeholder="••••••••"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                        placeholder="••••••••"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full h-11 rounded-lg text-white font-semibold text-sm transition-all shadow-md hover:opacity-95"
                    style="background-color: #00A8B8;"
                >
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
