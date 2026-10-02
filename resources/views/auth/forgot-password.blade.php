@extends('layouts.app')

@section('title', 'Forgot Password - ShopPulss')

@section('content')
<div class="min-h-[65vh] py-12 px-4 flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-1.5 justify-center mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-black" style="background-color: #00A8B8;">S</div>
                    <span class="text-2xl font-black tracking-tight" style="color: #0F1B4D;">Shop<span style="color: #00A8B8;">Pulss</span></span>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Forgot Password</h1>
                <p class="text-xs text-gray-500 mt-1">Enter your email address and we'll send you a password reset link.</p>
            </div>

            @if(session('status'))
                <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs font-medium text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                        placeholder="you@example.com"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full h-11 rounded-lg text-white font-semibold text-sm transition-all shadow-md hover:opacity-95"
                    style="background-color: #0F1B4D;"
                >
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
                Remember your password?
                <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 ml-1">Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
