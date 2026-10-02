@extends('layouts.app')

@section('title', '403 - Access Denied | ShopPulss')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16 bg-gray-50">
    <div class="max-w-md w-full text-center bg-white rounded-2xl border border-gray-100 p-8 md:p-12 shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 text-2xl font-black">
            403
        </div>
        <h1 class="text-xl md:text-2xl font-black text-gray-900 mb-2" style="color: #0F1B4D;">Access Denied</h1>
        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
            {{ $exception->getMessage() ?: 'You do not have administrative permission to view this restricted area.' }}
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl text-white text-xs font-semibold shadow-sm hover:opacity-95 transition-all" style="background-color: #0F1B4D;">
                Storefront Home
            </a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl text-white text-xs font-semibold shadow-sm hover:opacity-95 transition-all" style="background-color: #00A8B8;">
                        Admin Dashboard
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
