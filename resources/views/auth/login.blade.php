@extends('layouts.app')

@section('title', 'Sign In - ShopPulss')

@section('content')
<div class="min-h-[70vh] py-12 px-4 flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full">
        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-1.5 justify-center mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-black" style="background-color: #00A8B8;">S</div>
                    <span class="text-2xl font-black tracking-tight" style="color: #0F1B4D;">Shop<span style="color: #00A8B8;">Pulss</span></span>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Welcome Back</h1>
                <p class="text-xs text-gray-500 mt-1">Sign in to your account to view your orders and track delivery</p>
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

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
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

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-gray-700">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">Forgot?</a>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-gray-300">
                        <span class="text-xs text-gray-600 font-medium">Keep me signed in</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full h-11 rounded-lg text-white font-semibold text-sm transition-all shadow-md hover:opacity-95"
                    style="background-color: #0F1B4D;"
                >
                    Sign In
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
                Don't have an account yet?
                <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:text-teal-700 ml-1">Create Account</a>
            </div>

            {{-- Demo credentials hint for convenience --}}
            <div class="mt-6 p-3 bg-blue-50/70 rounded-xl border border-blue-100 text-[11px] text-blue-900 leading-relaxed">
                <span class="font-bold text-blue-950">🔑 Demo Admin Login:</span><br>
                Email: <code class="bg-blue-100/60 px-1 py-0.5 rounded">admin@shoppulss.com</code> | Password: <code class="bg-blue-100/60 px-1 py-0.5 rounded">password</code>
            </div>
        </div>
    </div>
</div>
@endsection
