@extends('layouts.admin')

@section('title', $staffMember->name . ' — Staff Profile')
@section('header', 'Staff Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-500">
        <a href="{{ route('admin.staff.index') }}" class="hover:text-gray-900">← Back to Staff</a>
        <span class="text-gray-300">/</span>
        <span>{{ $staffMember->name }}</span>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <div class="flex items-center gap-5 pb-6 border-b border-gray-100">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-black text-white flex-shrink-0"
                 style="background: linear-gradient(135deg, #0F1B4D, #00A8B8);">
                {{ strtoupper(substr($staffMember->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">{{ $staffMember->name }}</h1>
                <div class="flex flex-wrap gap-1 mt-1">
                    @forelse($staffMember->roles as $role)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                            {{ $role->name }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-400 italic">No role assigned</span>
                    @endforelse
                </div>
            </div>
            <div class="ml-auto">
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ ($staffMember->status ?? 'active') === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($staffMember->status ?? 'active') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-6 text-xs">
            <div>
                <span class="text-gray-400 font-semibold block mb-0.5">Email Address</span>
                <a href="mailto:{{ $staffMember->email }}" class="font-bold text-teal-600 hover:underline">{{ $staffMember->email }}</a>
            </div>
            <div>
                <span class="text-gray-400 font-semibold block mb-0.5">Phone</span>
                <span class="font-bold text-gray-800">{{ $staffMember->phone ?? '—' }}</span>
            </div>
            <div>
                <span class="text-gray-400 font-semibold block mb-0.5">Account ID</span>
                <span class="font-bold text-gray-800 font-mono">#{{ $staffMember->id }}</span>
            </div>
            <div>
                <span class="text-gray-400 font-semibold block mb-0.5">Account Created</span>
                <span class="font-bold text-gray-800">{{ $staffMember->created_at->format('M d, Y — h:i A') }}</span>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.staff.edit', $staffMember->id) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white font-semibold text-xs shadow-sm hover:opacity-95 transition-all"
           style="background-color: #00A8B8;">
            Edit Staff Account
        </a>
        <a href="{{ route('admin.staff.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-gray-700 bg-white border border-gray-200 font-semibold text-xs hover:bg-gray-50 transition-colors">
            Back to List
        </a>
    </div>

</div>
@endsection
