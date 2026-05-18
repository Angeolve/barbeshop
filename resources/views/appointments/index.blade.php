<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                {{ Auth::user()->role === 'client' ? __('Mis Citas Agendadas') : __('Agenda Global de Citas') }}
            </h2>
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-black font-black text-xs uppercase tracking-wider rounded-xl shadow-md shadow-amber-500/20 transition-all duration-200 hover:shadow-lg hover:shadow-amber-500/30 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Agendar Nueva Cita
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 bg-green-50 border-l-4 border-green-500 text-green-800 px-5 py-4 rounded-xl shadow-sm flex items-start gap-3" role="alert">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold text-sm">¡Éxito!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-amber-500/10">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-500/10">
                                @if(Auth::user()->role !== 'client')
                                    <th class="px-5 py-4 text-left text-[11px] font-black text-amber-700 uppercase tracking-widest">Cliente</th>
                                @endif
                                <th class="px-5 py-4 text-left text-[11px] font-black text-amber-700 uppercase tracking-widest">Barbero</th>
                                <th class="px-5 py-4 text-left text-[11px] font-black text-amber-700 uppercase tracking-widest">Servicio</th>
                                <th class="px-5 py-4 text-left text-[11px] font-black text-amber-700 uppercase tracking-widest">Fecha y Hora</th>
                                <th class="px-5 py-4 text-left text-[11px] font-black text-amber-700 uppercase tracking-widest">Estado</th>
                                <th class="px-5 py-4 text-center text-[11px] font-black text-amber-700 uppercase tracking-widest">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($appointments as $appointment)
                                <tr class="hover:bg-amber-50/40 transition-colors duration-100">

                                    @if(Auth::user()->role !== 'client')
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2.5">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-black font-extrabold text-xs shrink-0">
                                                    {{ substr($appointment->client->name ?? 'C', 0, 1) }}
                                                </div>
                                                <span class="font-semibold text-sm text-gray-900">{{ $appointment->client->name ?? '—' }}</span>
                                            </div>
                                        </td>
                                    @endif

                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                        {{ $appointment->barber->name ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">{{ $appointment->service->name ?? '—' }}</span>
                                        @if($appointment->service)
                                            <p class="text-[11px] text-gray-400">${{ number_format($appointment->service->price, 2) }}</p>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-800">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d/m/Y') }}
                                        </span>
                                        <p class="text-[11px] text-amber-600 font-bold">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($appointment->status === 'scheduled')
                                            <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-black rounded-full bg-amber-100 text-amber-800 uppercase tracking-wider">Programada</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-black rounded-full bg-green-100 text-green-800 uppercase tracking-wider">Completada</span>
                                        @elseif($appointment->status === 'canceled')
                                            <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-black rounded-full bg-red-100 text-red-800 uppercase tracking-wider">Cancelada</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-black rounded-full bg-gray-100 text-gray-600 uppercase tracking-wider">{{ $appointment->status }}</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- Admin/Staff: marcar como completada o cancelar --}}
                                            @if(Auth::user()->role !== 'client' && $appointment->status === 'scheduled')
                                                <form action="{{ route('appointments.status', $appointment->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" title="Marcar como Completada" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition duration-150">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </button>
                                                </form>

                                                <form action="{{ route('appointments.status', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Cancelar esta cita?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="canceled">
                                                    <button type="submit" title="Cancelar Cita" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-150">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Cliente: solo puede cancelar su cita programada --}}
                                            @if(Auth::user()->role === 'client' && $appointment->status === 'scheduled')
                                                <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas cancelar tu cita?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" title="Cancelar Mi Cita" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-150">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Sin acciones si ya está completada o cancelada --}}
                                            @if($appointment->status !== 'scheduled')
                                                <span class="text-gray-300 italic text-[11px]">—</span>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <p class="text-gray-400 font-semibold text-sm">No hay citas agendadas en este momento.</p>
                                            <a href="{{ route('appointments.create') }}" class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-black font-black text-xs uppercase tracking-wider rounded-lg shadow-sm hover:from-amber-600 hover:to-orange-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9"/></svg>
                                                Agendar Primera Cita
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>