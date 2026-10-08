@extends('layouts.app')

@section('title', 'Create Account - ShopPulss')
@section('description', 'Join ShopPulss for easy order tracking and personalized customer support.')
@section('robots', 'noindex, follow')

@section('content')
<div class="min-h-[75vh] py-14 px-4 flex items-center justify-center bg-[#F6F7FB]">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-sp-card border border-[#E6E8F2] p-8 sm:p-9">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block mb-3" aria-label="ShopPulss Home">
                    <img
                        src="{{ asset('images/shoppulss-logo.svg') }}"
                        alt="ShopPulss"
                        width="165"
                        height="38"
                        class="site-logo-header h-9 sm:h-10 w-auto mx-auto max-w-[165px] object-contain"
                        style="height: 36px; max-height: 40px; width: auto; max-width: 165px; object-fit: contain;"
                    >
                </a>
                <h1 class="text-2xl font-black text-[#0F1654]">Create an Account</h1>
                <p class="text-xs text-gray-500 mt-1">Join ShopPulss for expedited checkout, real-time tracking, and exclusive discounts</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error mb-5">
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="sp-label">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="sp-input"
                        placeholder="Ali Khan"
                    >
                </div>

                <div>
                    <label for="email" class="sp-label">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="sp-input"
                        placeholder="ali@example.com"
                    >
                </div>

                <div>
                    <label for="phone" class="sp-label">Phone Number (Optional)</label>
                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="sp-input font-mono"
                        placeholder="03001234567"
                    >
                </div>

                <div>
                    <label for="password" class="sp-label">Password (min 8 characters)</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="sp-input"
                        placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="sp-label">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="sp-input"
                        placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢"
                    >
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full py-3.5 text-sm mt-3"
                    id="register-submit-btn"
                >
                    <span>Create My Account</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-[#E6E8F2] text-center text-xs text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-[#FF5A1F] hover:underline ml-1">Sign In</a>
            </div>
        </div>
    </div>
</div>
@endsection
