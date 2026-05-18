<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
            {{ __('Horarios de Barberos') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        editModal: false, 
        selectedBarberId: '', 
        selectedBarberName: '', 
        shift: 'mañana',
        monday: true,
        tuesday: true,
        wednesday: true,
        thursday: true,
        friday: true,
        saturday: true,
        sunday: false,
        
        openEdit(barber) {
            this.selectedBarberId = barber.id;
            this.selectedBarberName = barber.name;
            this.shift = barber.schedule ? barber.schedule.shift : 'mañana';
            this.monday = barber.schedule ? !!barber.schedule.monday : true;
            this.tuesday = barber.schedule ? !!barber.schedule.tuesday : true;
            this.wednesday = barber.schedule ? !!barber.schedule.wednesday : true;
            this.thursday = barber.schedule ? !!barber.schedule.thursday : true;
            this.friday = barber.schedule ? !!barber.schedule.friday : true;
            this.saturday = barber.schedule ? !!barber.schedule.saturday : true;
            this.sunday = barber.schedule ? !!barber.schedule.sunday : false;
            this.editModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 border-l-4 border-green-500 text-green-700 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Table of Barber Schedules -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Agenda Semanal de Disponibilidad</h3>
                        <p class="text-sm text-gray-500 mt-1">Configura qué días laboran tus barberos y su turno correspondiente (8 horas de jornada, última cita a las 6:00 PM).</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-amber-500/10">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-extrabold text-amber-600 uppercase tracking-widest">Barbero</th>
                                <th scope="col" class="px-6 py-4 class text-left text-xs font-extrabold text-amber-600 uppercase tracking-widest">Turno Activo</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-extrabold text-amber-600 uppercase tracking-widest">Horas de Trabajo</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-extrabold text-amber-600 uppercase tracking-widest">Días Laborales</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-extrabold text-amber-600 uppercase tracking-widest">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($barbers as $barber)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <!-- Barber Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-black font-black text-sm shadow-sm shrink-0">
                                                {{ substr($barber->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $barber->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $barber->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Active Shift Badge -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($barber->schedule && $barber->schedule->shift === 'noche')
                                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-black bg-purple-100 text-orange-700 tracking-wider uppercase">
                                                🌙 Noche
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-black bg-blue-100 text-amber-700 tracking-wider uppercase">
                                                ☀️ Mañana
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Shift Hours (8 hours, last appt at 6) -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-semibold">
                                        @if($barber->schedule && $barber->schedule->shift === 'noche')
                                            10:00 AM — 6:00 PM
                                            <span class="block text-[10px] text-gray-400 font-medium">(Cita final: 6:00 PM)</span>
                                        @else
                                            9:00 AM — 5:00 PM
                                            <span class="block text-[10px] text-gray-400 font-medium">(Cita final: 5:00 PM)</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Work Days (Mon-Sun) Indicator Grid -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @php
                                                $days = [
                                                    'monday' => 'L',
                                                    'tuesday' => 'M',
                                                    'wednesday' => 'X',
                                                    'thursday' => 'J',
                                                    'friday' => 'V',
                                                    'saturday' => 'S',
                                                    'sunday' => 'D'
                                                ];
                                            @endphp
                                            @foreach($days as $key => $letter)
                                                @php
                                                    $works = $barber->schedule ? $barber->schedule->$key : true;
                                                @endphp
                                                <span class="h-7 w-7 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-150
                                                    {{ $works 
                                                        ? 'bg-gradient-to-br from-amber-500 to-orange-500 text-black shadow-sm' 
                                                        : 'bg-gray-100 text-gray-300 border border-gray-200' }}"
                                                    title="{{ ucfirst($key) }}: {{ $works ? 'Labora' : 'Descanso' }}">
                                                    {{ $letter }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>

                                    <!-- Actions Button (Edit Icon Only) -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button @click="openEdit({{ json_encode($barber) }})" 
                                                class="p-2 text-gray-500 hover:text-amber-500 hover:bg-amber-500/5 rounded-lg transition"
                                                title="Editar Horario">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-semibold">
                                        No hay barberos registrados en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Alpine.js Edit Schedule Modal (Premium, Blurred Backdrop) -->
        <div x-show="editModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white rounded-2xl border border-amber-500/10 shadow-2xl max-w-md w-full overflow-hidden transform transition-all"
                 @click.away="editModal = false">
                
                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-black flex justify-between items-center">
                    <div>
                        <h3 class="font-extrabold text-lg uppercase tracking-wider">Configurar Horario</h3>
                        <p class="text-xs font-semibold text-black/70 leading-none mt-1" x-text="selectedBarberName"></p>
                    </div>
                    <button @click="editModal = false" class="text-black hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form :action="'/schedules/' + selectedBarberId" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Shift selection -->
                    <div>
                        <label for="modal_shift" class="block text-sm font-bold text-gray-700 mb-2">Turno Laboral (8 Horas)</label>
                        <select name="shift" id="modal_shift" x-model="shift" class="block w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                            <option value="mañana">☀️ Mañana (9:00 AM - 5:00 PM)</option>
                            <option value="noche">🌙 Noche (10:00 AM - 6:00 PM)</option>
                        </select>
                        <p class="text-[11px] text-gray-400 mt-2 font-medium">Nota: El turno de noche finaliza exactamente a las 6:00 PM (hora de la última cita permitida).</p>
                    </div>

                    <!-- Work days checkboxes -->
                    <div>
                        <span class="block text-sm font-bold text-gray-700 mb-3">Días Laborales</span>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="monday" value="1" x-model="monday" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm font-semibold text-gray-700">Lunes</span>
                            </label>
                            
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="tuesday" value="1" x-model="tuesday" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm font-semibold text-gray-700">Martes</span>
                            </label>

                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="wednesday" value="1" x-model="wednesday" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm font-semibold text-gray-700">Miércoles</span>
                            </label>

                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="thursday" value="1" x-model="thursday" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm font-semibold text-gray-700">Jueves</span>
                            </label>

                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="friday" value="1" x-model="friday" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm font-semibold text-gray-700">Viernes</span>
                            </label>

                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="saturday" value="1" x-model="saturday" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm font-semibold text-gray-700">Sábado</span>
                            </label>

                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="sunday" value="1" x-model="sunday" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm font-semibold text-gray-700">Domingo</span>
                            </label>
                            
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="editModal = false" class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-xs font-black text-gray-600 uppercase tracking-wider hover:bg-gray-200 transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg text-xs font-black text-black uppercase tracking-wider shadow-md hover:from-amber-600 hover:to-orange-600 transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
