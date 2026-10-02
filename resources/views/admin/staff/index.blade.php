@extends('layouts.admin')

@section('title', 'Staff & Roles')
@section('header', 'Staff & Roles Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Staff Accounts ({{ $staff->total() }})</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage administrator and store staff accounts with role assignments</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00A8B8] hover:bg-[#008F9C] text-white text-xs font-bold rounded-xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Staff Member
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="flex flex-wrap items-center gap-3">
            {{-- Search input with properly centered icon --}}
            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search staff name, email or phone..."
                    class="w-full h-10 pl-10 pr-4 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs text-gray-800 placeholder-gray-400 transition-colors focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8]"
                >
            </div>

            {{-- Role filter --}}
            <select name="role" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[150px] cursor-pointer" onchange="this.form.submit()">
                <option value="">Role: All</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select name="status" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[130px] cursor-pointer" onchange="this.form.submit()">
                <option value="">Status: All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 px-4 rounded-xl text-white font-semibold text-xs shadow-xs hover:opacity-95 transition-opacity inline-flex items-center gap-1.5" style="background-color: #00A8B8;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['q', 'role', 'status']))
                    <a href="{{ route('admin.staff.index') }}" class="h-10 px-3.5 flex items-center justify-center rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:text-red-600 hover:border-red-200 hover:bg-red-50/30 transition-colors whitespace-nowrap" title="Reset Filters">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Staff Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Staff Member</th>
                        <th class="py-3.5 px-4">Contact</th>
                        <th class="py-3.5 px-4">Assigned Roles</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Joined</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                    @forelse($staff as $member)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0"
                                         style="background: linear-gradient(135deg, #0F1B4D, #00A8B8); color: white;">
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $member->name }}</div>
                                        <div class="text-[10px] text-gray-400">ID #{{ $member->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-gray-800">{{ $member->email }}</div>
                                <div class="text-[11px] text-gray-400">{{ $member->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($member->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-gray-400 italic">No role assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ ($member->status ?? 'active') === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    {{ ucfirst($member->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-400 text-[11px]">
                                {{ $member->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.staff.edit', $member->id) }}"
                                       class="px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 font-bold text-xs transition-colors">
                                        Edit
                                    </a>
                                    @if($member->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.staff.destroy', $member->id) }}"
                                              onsubmit="return confirm('Remove {{ $member->name }} from staff? This cannot be undone.');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-bold text-xs transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic text-[11px]">(You)</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-sm font-semibold">No staff members found</p>
                                <p class="text-xs mt-1">Try adjusting your filters or add a new staff member</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staff->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $staff->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
