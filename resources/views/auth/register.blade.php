@extends('layouts.app')

@section('title', 'Create Account - ShopPulss')

@section('content')
<div class="min-h-[75vh] py-12 px-4 flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-1.5 justify-center mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-black" style="background-color: #00A8B8;">S</div>
                    <span class="text-2xl font-black tracking-tight" style="color: #0F1B4D;">Shop<span style="color: #00A8B8;">Pulss</span></span>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Create an Account</h1>
                <p class="text-xs text-gray-500 mt-1">Join ShopPulss for faster checkout, order tracking, and exclusive perks</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs font-medium text-red-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                        placeholder="Ali Khan"
                    >
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                        placeholder="ali@example.com"
                    >
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1">Phone Number (Optional)</label>
                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                        placeholder="+92 300 1234567"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Password (min 8 chars)</label>
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
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">Confirm Password</label>
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
                    class="w-full h-11 rounded-lg text-white font-semibold text-sm transition-all shadow-md hover:opacity-95 mt-2"
                    style="background-color: #00A8B8;"
                >
                    Create My Account
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 ml-1">Sign In</a>
            </div>
        </div>
    </div>
</div>
@endsection
