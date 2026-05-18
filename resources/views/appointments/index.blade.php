<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ Auth::user()->role === 'client' ? __('Mis Citas Agendadas') : __('Agenda Global de Citas') }}
            </h2>
            
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 transition">
                + Agendar Nueva Cita
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow" role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(Auth::user()->role !== 'client')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barbero</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($appointments as $appointment)
                            <tr>
                                @if(Auth::user()->role !== 'client')
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $appointment->client->name }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $appointment->barber->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $appointment->service->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->status === 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                    @elseif($appointment->status === 'confirmed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmada</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-3">
                                        
                                        @if(Auth::user()->role !== 'client' && $appointment->status === 'pending')
                                            <form action="{{ route('appointments.status', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="text-green-600 hover:text-green-900 font-semibold">Confirmar</button>
                                            </form>
                                            
                                            <form action="{{ route('appointments.status', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Rechazar</button>
                                            </form>
                                        @endif

                                        @if(Auth::user()->role === 'client' && $appointment->status === 'pending')
                                            <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar tu cita?');" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Cancelar Cita</button>
                                            </form>
                                        @endif

                                        @if($appointment->status !== 'pending')
                                            <span class="text-gray-400 italic text-xs">Sin acciones</span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay citas agendadas en este momento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>