<div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-4">
    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: #0F1B4D;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <div class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <nav class="space-y-1 text-xs font-semibold">
        <a
            href="{{ route('account.dashboard') }}"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('account.dashboard') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        <a
            href="{{ route('account.orders') }}"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('account.orders*') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            My Orders
        </a>

        <a
            href="{{ route('account.profile') }}"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('account.profile') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-gray-600 hover:bg-gray-50' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profile & Security
        </a>

        <div class="pt-2 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </nav>
</div>
