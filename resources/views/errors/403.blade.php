@extends('layouts.app')

@section('title', '403 - Access Denied | ShopPulss')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16 bg-[#F6F7FB]">
    <div class="max-w-md w-full text-center bg-white rounded-3xl border border-[#E6E8F2] p-8 md:p-12 shadow-sp-card">
        <div class="w-20 h-20 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-5 text-2xl font-black shadow-xs">
            403
        </div>
        <h1 class="text-2xl font-black text-[#0F1654] mb-2">Access Denied</h1>
        <p class="text-xs text-gray-500 mb-7 leading-relaxed">
            You do not have permission to access this secure portal area.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn-primary">
                Return to Storefront
            </a>
        </div>
    </div>
</div>
@endsection
