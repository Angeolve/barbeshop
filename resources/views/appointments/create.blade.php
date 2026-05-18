<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agendar Nueva Cita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf

                    @if(in_array(Auth::user()->role, ['admin', 'staff']))
                        <div class="mb-4">
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Seleccionar Cliente</label>
                            <select name="client_id" id="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- Selecciona un Cliente --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="service_id" class="block text-sm font-medium text-gray-700">Servicio Requerido</label>
                            <select name="service_id" id="service_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- Elige un Servicio --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} — ${{ number_format($service->price, 2) }} ({{ $service->duration_minutes }} min)
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="barber_id" class="block text-sm font-medium text-gray-700">Barbero / Staff</label>
                            <select name="barber_id" id="barber_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- Elige un Barbero --</option>
                                @foreach($barbers as $barber)
                                    <option value="{{ $barber->id }}" {{ old('barber_id') == $barber->id ? 'selected' : '' }}>
                                        {{ $barber->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barber_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700">Fecha y Hora Preferida</label>
                        <input type="datetime-local" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @error('appointment_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ Auth::user()->role === 'client' ? route('appointments.client_index') : route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            Agendar Cita
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>