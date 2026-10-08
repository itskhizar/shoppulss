@extends('layouts.app')

@section('title', 'Forgot Password - ShopPulss')

@section('content')
<div class="min-h-[65vh] py-14 px-4 flex items-center justify-center bg-[#F6F7FB]">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-sp-card border border-[#E6E8F2] p-8 sm:p-9">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/shoppulss-logo.png') }}" alt="ShopPulss" class="h-10 w-auto mx-auto object-contain">
                </a>
                <h1 class="text-2xl font-black text-[#0F1654]">Forgot Password</h1>
                <p class="text-xs text-gray-500 mt-1">Enter your registered email address and we'll send you a password reset link.</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success mb-5">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error mb-5">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="sp-label">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="sp-input"
                        placeholder="you@example.com"
                    >
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full py-3.5 text-sm mt-2"
                >
                    <span>Send Reset Link</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-[#E6E8F2] text-center text-xs text-gray-500">
                Remember your password?
                <a href="{{ route('login') }}" class="font-bold text-[#FF5A1F] hover:underline ml-1">Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
