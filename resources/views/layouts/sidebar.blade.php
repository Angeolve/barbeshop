<!-- Sidebar (Fixed Left, Soft Luxury Dark Gray Theme) -->
<nav class="hidden md:flex flex-col w-64 bg-[#18181b] h-screen shadow-2xl border-r border-amber-500/10 justify-between flex-shrink-0 z-20">
    <div>
        <!-- Brand / Logo (Luxury Gold & Orange Barbershop Theme) -->
        <div class="flex items-center gap-3 h-20 px-6 border-b border-amber-500/10 bg-[#18181b] shrink-0">
            <svg class="w-9 h-9" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="goldOrangeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#fbbf24" />
                        <stop offset="100%" stop-color="#ea580c" />
                    </linearGradient>
                </defs>
                <circle cx="12" cy="12" r="10" fill="url(#goldOrangeGrad)" />
                <path d="M9.5 8.5L16 12L9.5 15.5V8.5Z" fill="#000000" />
            </svg>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-500 to-orange-500 text-xl font-black tracking-wider">Barbería</span>
        </div>

        <!-- Navigation Links -->
        <div class="px-3 py-6 flex flex-col gap-1.5">
            <!-- Dashboard / Inicio -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-150 rounded-lg group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-black shadow-lg shadow-amber-500/10' : 'text-gray-400 hover:text-amber-400 hover:bg-[#202024]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-black' : 'text-gray-500 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Subtitle Category: GESTIÓN -->
            <div class="mt-6 mb-2 px-4">
                <span class="text-[11px] font-black text-orange-500/80 uppercase tracking-widest block">Gestión</span>
            </div>

            <!-- Usuarios (Group Icon) - visible only to admin -->
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-150 rounded-lg group {{ request()->routeIs('users.index') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-black shadow-lg shadow-amber-500/10' : 'text-gray-400 hover:text-amber-400 hover:bg-[#202024]' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('users.index') ? 'text-black' : 'text-gray-500 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Usuarios</span>
                </a>
            @endif

            <!-- Clientes / Pacientes (User Icon) -->
            <a href="{{ route('clients.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-150 rounded-lg group {{ request()->routeIs('clients.*') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-black shadow-lg shadow-amber-500/10' : 'text-gray-400 hover:text-amber-400 hover:bg-[#202024]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('clients.*') ? 'text-black' : 'text-gray-500 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Clientes</span>
            </a>

            <!-- Barberos / Staff / Doctores (Doctor/User Icon) -->
            <a href="{{ route('staff.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-150 rounded-lg group {{ request()->routeIs('staff.*') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-black shadow-lg shadow-amber-500/10' : 'text-gray-400 hover:text-amber-400 hover:bg-[#202024]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('staff.*') ? 'text-black' : 'text-gray-500 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Doctores / Barberos</span>
            </a>

            <!-- Horarios de Barberos (Clock Icon) - visible only to admin -->
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('schedules.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-150 rounded-lg group {{ request()->routeIs('schedules.*') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-black shadow-lg shadow-amber-500/10' : 'text-gray-400 hover:text-amber-400 hover:bg-[#202024]' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('schedules.*') ? 'text-black' : 'text-gray-500 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Horarios Barberos</span>
                </a>
            @endif

            <!-- Servicios (List/Tag Icon) -->
            <a href="{{ route('services.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-150 rounded-lg group {{ request()->routeIs('services.*') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-black shadow-lg shadow-amber-500/10' : 'text-gray-400 hover:text-amber-400 hover:bg-[#202024]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('services.*') ? 'text-black' : 'text-gray-500 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Servicios</span>
            </a>

            <!-- Citas Agendadas / Citas Médicas (Calendar Check Icon) -->
            <a href="{{ Auth::user()->role === 'client' ? route('appointments.client_index') : route('appointments.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all duration-150 rounded-lg group {{ request()->routeIs('appointments.*') ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-black shadow-lg shadow-amber-500/10' : 'text-gray-400 hover:text-amber-400 hover:bg-[#202024]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('appointments.*') ? 'text-black' : 'text-gray-500 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Citas</span>
            </a>
        </div>
    </div>

    <!-- Clean user signature at bottom with Logout Button -->
    <div class="border-t border-amber-500/10 p-4 bg-[#121214] flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-black font-extrabold text-xs shadow-md shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-amber-500/70 uppercase tracking-widest leading-none">Usuario</p>
                <p class="text-xs font-semibold text-gray-300 truncate leading-tight mt-1">{{ Auth::user()->name }}</p>
            </div>
        </div>
        
        <!-- Desktop Logout Icon Button -->
        <form method="POST" action="{{ route('logout') }}" x-data class="inline shrink-0">
            @csrf
            <button type="submit" title="Cerrar Sesión" class="p-1.5 text-gray-500 hover:text-red-500 hover:bg-[#202024] rounded-lg transition duration-150">
                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</nav>

<!-- Mobile Header + Navigation Menu Toggle (Visible only on small screens) -->
<div class="md:hidden flex items-center justify-between bg-[#18181b] p-4 border-b border-amber-500/10 w-full" x-data="{ mobileOpen: false }">
    <div class="flex items-center gap-2">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="url(#goldOrangeGradMobile)" />
            <defs>
                <linearGradient id="goldOrangeGradMobile" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#fbbf24" />
                    <stop offset="100%" stop-color="#ea580c" />
                </linearGradient>
            </defs>
            <path d="M9.5 8.5L16 12L9.5 15.5V8.5Z" fill="#000000" />
        </svg>
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-500 to-orange-500 font-extrabold tracking-wider text-sm">Barbería</span>
    </div>
    <button @click="mobileOpen = !mobileOpen" class="text-amber-500 hover:text-orange-500 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
    
    <!-- Mobile Dropdown Menu -->
    <div x-show="mobileOpen" @click.away="mobileOpen = false" class="absolute top-16 left-0 right-0 bg-[#18181b] border-b border-amber-500/10 shadow-2xl z-50 p-4 flex flex-col gap-2">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-amber-400 hover:bg-[#202024] rounded-lg">Dashboard</a>
        
        <div class="my-2 border-t border-amber-500/10 pt-2">
            <span class="block px-4 text-xs font-black text-orange-500/80 uppercase tracking-widest mb-1">Gestión</span>
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-amber-400 hover:bg-[#202024] rounded-lg">Usuarios</a>
            @endif
            <a href="{{ route('clients.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-amber-400 hover:bg-[#202024] rounded-lg">Clientes</a>
            <a href="{{ route('staff.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-amber-400 hover:bg-[#202024] rounded-lg">Doctores / Barberos</a>
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('schedules.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-amber-400 hover:bg-[#202024] rounded-lg">Horarios Barberos</a>
            @endif
            <a href="{{ route('services.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-amber-400 hover:bg-[#202024] rounded-lg">Servicios</a>
            <a href="{{ Auth::user()->role === 'client' ? route('appointments.client_index') : route('appointments.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-amber-400 hover:bg-[#202024] rounded-lg">Citas</a>
        </div>

        <div class="my-2 border-t border-amber-500/10 pt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm font-semibold text-red-500 hover:text-red-600 hover:bg-[#202024] rounded-lg">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</div>
