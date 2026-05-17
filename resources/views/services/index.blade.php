<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Servicios') }}
            </h2>
            <a href="{{ route('services.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                + Nuevo Servicio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-rShadow shadow" role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($services as $service)
                            <tr class="{{ $service->trashed() ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $service->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $service->description ?? 'Sin descripción' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($service->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $service->duration_minutes }} min</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($service->trashed())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo (Soft Delete)</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex justify-center space-x-3">
                                    <a href="{{ route('services.edit', $service->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</a>
                                    
                                    @if($service->trashed())
                                        <form action="{{ route('services.restore', $service->id) }}" method="POST" onsubmit="return confirm('¿Deseas reactivar este servicio?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-semibold">Restaurar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas deshabilitar este servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Deshabilitar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay servicios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>