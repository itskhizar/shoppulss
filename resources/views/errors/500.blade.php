@extends('layouts.app')

@section('title', '500 - Server Error | ShopPulss')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16 bg-gray-50">
    <div class="max-w-md w-full text-center bg-white rounded-2xl border border-gray-100 p-8 md:p-12 shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 text-2xl font-black">
            500
        </div>
        <h1 class="text-xl md:text-2xl font-black text-gray-900 mb-2" style="color: #0F1B4D;">Something Went Wrong</h1>
        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
            Our team has been automatically alerted and we are working to resolve this issue. Please try again in a moment.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl text-white text-xs font-semibold shadow-sm hover:opacity-95 transition-all" style="background-color: #0F1B4D;">
                Return to Homepage
            </a>
            <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                Browse Catalog
            </a>
        </div>
    </div>
</div>
@endsection
