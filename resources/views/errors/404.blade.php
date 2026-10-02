@extends('layouts.app')

@section('title', '404 - Page Not Found | ShopPulss')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16 bg-[#F6F7FB]">
    <div class="max-w-md w-full text-center bg-white rounded-3xl border border-[#E6E8F2] p-8 md:p-12 shadow-sp-card">
        <div class="w-20 h-20 rounded-2xl bg-[#FFF1EA] text-[#FF5A1F] flex items-center justify-center mx-auto mb-5 text-2xl font-black shadow-xs">
            404
        </div>
        <h1 class="text-2xl font-black text-[#0F1654] mb-2">Page Not Found</h1>
        <p class="text-xs text-gray-500 mb-7 leading-relaxed">
            The page you are looking for might have been moved, renamed, or is temporarily unavailable.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn-primary">
                Return to Homepage
            </a>
            <a href="{{ route('products.index') }}" class="btn-secondary">
                Browse Products
            </a>
        </div>
    </div>
</div>
@endsection
