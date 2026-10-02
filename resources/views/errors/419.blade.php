@extends('layouts.app')

@section('title', '419 - Page Expired | ShopPulss')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16 bg-gray-50">
    <div class="max-w-md w-full text-center bg-white rounded-2xl border border-gray-100 p-8 md:p-12 shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 text-2xl font-black">
            419
        </div>
        <h1 class="text-xl md:text-2xl font-black text-gray-900 mb-2" style="color: #0F1B4D;">Session Expired</h1>
        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
            Your security token expired due to inactivity. Please refresh the page and try again.
        </p>
        <div class="flex gap-3 justify-center">
            <button onclick="window.location.reload()" class="px-5 py-2.5 rounded-xl text-white text-xs font-semibold shadow-sm hover:opacity-95 transition-all" style="background-color: #00A8B8;">
                Refresh Page
            </button>
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                Home
            </a>
        </div>
    </div>
</div>
@endsection
