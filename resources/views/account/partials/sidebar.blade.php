<div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 shadow-sp-card space-y-4">
    <div class="flex items-center gap-3 pb-4 border-b border-[#E6E8F2]">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-white text-sm font-black shadow-sm bg-gradient-to-br from-[#0F1654] to-[#16206E]">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <div class="text-sm font-black text-[#0F1654] truncate">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</div>
            <div class="text-[10px] text-[#0AA6B7] font-bold mt-0.5">{{ Auth::user()->isAdmin() ? 'Administrator' : 'Verified Customer' }}</div>
        </div>
    </div>

    <nav class="space-y-1.5 text-xs font-bold">
        <a
            href="{{ route('account.dashboard') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('account.dashboard') ? 'bg-[#FFF1EA] text-[#FF5A1F] shadow-xs' : 'text-gray-600 hover:bg-[#F6F7FB] hover:text-[#0F1654]' }}"
        >
            <svg class="w-4 h-4 {{ request()->routeIs('account.dashboard') ? 'text-[#FF5A1F]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('account.orders') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('account.orders*') ? 'bg-[#FFF1EA] text-[#FF5A1F] shadow-xs' : 'text-gray-600 hover:bg-[#F6F7FB] hover:text-[#0F1654]' }}"
        >
            <svg class="w-4 h-4 {{ request()->routeIs('account.orders*') ? 'text-[#FF5A1F]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>My Orders</span>
        </a>

        <a
            href="{{ route('account.profile') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('account.profile') ? 'bg-[#FFF1EA] text-[#FF5A1F] shadow-xs' : 'text-gray-600 hover:bg-[#F6F7FB] hover:text-[#0F1654]' }}"
        >
            <svg class="w-4 h-4 {{ request()->routeIs('account.profile') ? 'text-[#FF5A1F]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Profile & Security</span>
        </a>

        @if(Auth::user()->isAdmin())
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#0AA6B7] hover:bg-teal-50 transition-all font-bold"
            >
                <svg class="w-4 h-4 text-[#0AA6B7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Admin Dashboard</span>
            </a>
        @endif

        <div class="pt-2 border-t border-[#E6E8F2]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-red-600 hover:bg-red-50 text-left transition-colors font-bold">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </nav>
</div>
