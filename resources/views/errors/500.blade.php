@extends('layouts.app')

@section('title', '500 - Server Error | ShopPulss')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16 bg-[#F6F7FB]">
    <div class="max-w-md w-full text-center bg-white rounded-3xl border border-[#E6E8F2] p-8 md:p-12 shadow-sp-card">
        <div class="w-20 h-20 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-5 text-2xl font-black shadow-xs">
            500
        </div>
        <h1 class="text-2xl font-black text-[#0F1654] mb-2">Something Went Wrong</h1>
        <p class="text-xs text-gray-500 mb-7 leading-relaxed">
            Our systems encountered an unexpected condition. Our technical support team has been notified.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn-primary">
                Return to Storefront
            </a>
            <a href="https://wa.me/923000000000" target="_blank" class="btn-secondary">
                WhatsApp Support
            </a>
        </div>
    </div>
</div>
@endsection
