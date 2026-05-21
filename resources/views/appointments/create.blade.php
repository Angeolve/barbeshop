<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
            {{ __('Agendar Nueva Cita') }}
        </h2>
    </x-slot>

        {{--
                Vista: appointments.create
                Comentarios: Esta vista usa AlpineJS para manejar la lógica de selección
                dinámica de barberos, fecha y franjas horarias. La variable `barbersData`
                proviene del controlador y debe incluir banderas por día y el `shift`.
                Guía rápida:
                - Para añadir un campo nuevo al formulario, agrégalo dentro del
                    `<form>` y en `AppointmentController@store` actualiza las reglas y
                    el array pasado a `Appointment::create([...])`.
                - Si modificas la estructura de `barbersData`, actualiza aquí los
                    accesores de Alpine (por ejemplo `activeBarber` y `timeSlots`).
        --}}
        <div class="py-12" x-data='{
        barbers: @json($barbersData),
        services: @json($services),
        clients: @json($clients),
        currentUserRole: "{{ Auth::user()->role }}",
        currentUserId: "{{ Auth::id() }}",
        
        selectedClientId: "",
        selectedServiceId: "",
        selectedBarberId: "",
        selectedDate: "",
        selectedTimeSlot: "",
        filterMode: "barber",
        
        daysOfWeekMap: ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"],
        daysOfWeekSpanish: {
            "monday": "Lunes",
            "tuesday": "Martes",
            "wednesday": "Miércoles",
            "thursday": "Jueves",
            "friday": "Viernes",
            "saturday": "Sábado",
            "sunday": "Domingo"
        },

        init() {
            if (this.currentUserRole === "client") {
                this.selectedClientId = this.currentUserId;
            }
        },

        get activeBarber() {
            var self = this;
            return this.barbers.find(function(b) { return b.id == self.selectedBarberId; });
        },

        get isDateValidForBarber() {
            if (!this.selectedDate || !this.selectedBarberId) return true;
            
            const dateObj = new Date(this.selectedDate + "T00:00:00");
            const dayName = this.daysOfWeekMap[dateObj.getDay()];
            const barber = this.activeBarber;
            
            return barber ? !!barber[dayName] : false;
        },

        get barbersWorkingOnSelectedDate() {
            if (!this.selectedDate) return [];
            
            const dateObj = new Date(this.selectedDate + "T00:00:00");
            const dayName = this.daysOfWeekMap[dateObj.getDay()];
            var self = this;
            
            return this.barbers.filter(function(b) { return !!b[dayName]; });
        },

        get timeSlots() {
            if (!this.selectedBarberId) return [];
            
            const barber = this.activeBarber;
            if (!barber) return [];
            
            const slots = [];
            
            if (barber.shift === "noche") {
                let hour = 10;
                let minutes = 0;
                while (hour != 18 || minutes != 30) {
                    const labelHour = hour == 9 || hour == 10 || hour == 11 || hour == 12 ? hour : hour - 12;
                    const ampm = hour == 9 || hour == 10 || hour == 11 ? "AM" : "PM";
                    const timeString = hour.toString().padStart(2, "0") + ":" + minutes.toString().padStart(2, "0") + ":00";
                    const labelString = labelHour + ":" + minutes.toString().padStart(2, "0") + " " + ampm;
                    slots.push({ value: timeString, label: labelString });
                    
                    minutes += 30;
                    if (minutes === 60) {
                        minutes = 0;
                        hour += 1;
                    }
                }
            } else {
                let hour = 9;
                let minutes = 0;
                while (hour != 17 || minutes != 30) {
                    const labelHour = hour == 9 || hour == 10 || hour == 11 || hour == 12 ? hour : hour - 12;
                    const ampm = hour == 9 || hour == 10 || hour == 11 ? "AM" : "PM";
                    const timeString = hour.toString().padStart(2, "0") + ":" + minutes.toString().padStart(2, "0") + ":00";
                    const labelString = labelHour + ":" + minutes.toString().padStart(2, "0") + " " + ampm;
                    slots.push({ value: timeString, label: labelString });
                    
                    minutes += 30;
                    if (minutes === 60) {
                        minutes = 0;
                        hour += 1;
                    }
                }
            }
            return slots;
        }
    }'>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-amber-500/10">
                
                <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- TITLE & INSTRUCTIONS -->
                    <div class="border-b border-amber-500/10 pb-4">
                        <h3 class="text-xl font-bold text-gray-900">Programar Servicio</h3>
                        <p class="text-xs text-gray-500 mt-1">Completa los campos para reservar tu turno. Las citas se programan en intervalos de 30 minutos según la disponibilidad activa del barbero.</p>
                    </div>

                    <!-- 1. SELECT REGISTERED CLIENT (NOMBRE) -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="client_id" class="block text-sm font-bold text-gray-700">Seleccionar Cliente</label>
                            
                            <!-- Option to Register new client immediately -->
                            <a href="{{ route('clients.create') }}" class="text-xs font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 transition flex items-center gap-1">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                + Dar de Alta a Cliente
                            </a>
                        </div>
                        
                        @if(Auth::user()->role === 'client')
                            <!-- Client Role: Lock selection to themselves -->
                            <input type="hidden" name="client_id" value="{{ Auth::id() }}">
                            <div class="p-3 bg-gray-50 rounded-lg border border-amber-500/10 text-sm font-semibold text-gray-700">
                                {{ Auth::user()->name }} ({{ Auth::user()->email }})
                            </div>
                        @else
                            <!-- Admin/Staff Role: Selection mandatory -->
                            <select name="client_id" id="client_id" x-model="selectedClientId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                <option value="">-- Selecciona el Nombre del Cliente --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->name }} ({{ $client->phone ?? 'Sin teléfono' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">El cliente debe estar registrado previamente. Si no aparece, dale click a "Dar de Alta a Cliente" arriba.</p>
                        @endif
                        @error('client_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- 2. SELECT SERVICE -->
                        <div>
                            <label for="service_id" class="block text-sm font-bold text-gray-700 mb-1.5">Servicio a Realizar</label>
                            <select name="service_id" id="service_id" x-model="selectedServiceId" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                <option value="">-- Elige el Servicio --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">
                                        {{ $service->name }} — ${{ number_format($service->price, 2) }} ({{ $service->duration_minutes }} min)
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- 3. CHOOSE FILTERING MODE (TABS) -->
                        <div>
                            <span class="block text-sm font-bold text-gray-700 mb-2">Método de Reserva</span>
                            <div class="flex rounded-lg bg-gray-100 p-1 border border-gray-200">
                                <button type="button" 
                                        @click="filterMode = 'barber'; selectedDate = ''; selectedTimeSlot = '';" 
                                        :class="filterMode === 'barber' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                                        class="flex-1 py-1.5 text-xs font-black uppercase tracking-wider rounded-md transition duration-150">
                                    Por Barbero
                                </button>
                                <button type="button" 
                                        @click="filterMode = 'date'; selectedBarberId = ''; selectedDate = ''; selectedTimeSlot = '';" 
                                        :class="filterMode === 'date' ? 'bg-white text-black shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                                        class="flex-1 py-1.5 text-xs font-black uppercase tracking-wider rounded-md transition duration-150">
                                    Por Fecha
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-amber-500/10 pt-6">
                        
                        <!-- BARBER MODE VIEW -->
                        <template x-if="filterMode === 'barber'">
                            <div class="space-y-4 col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Step 1: Select Barber -->
                                <div>
                                    <label for="barber_id" class="block text-sm font-bold text-gray-700 mb-1.5">1. Seleccionar Barbero</label>
                                    <select name="barber_id" id="barber_id" x-model="selectedBarberId" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                        <option value="">-- Elige el Barbero --</option>
                                        <template x-for="barber in barbers" :key="barber.id">
                                            <option :value="barber.id" x-text="barber.name"></option>
                                        </template>
                                    </select>
                                    
                                    <!-- Barber shift indicator -->
                                    <div x-show="selectedBarberId" class="mt-3 p-3 bg-gray-50 rounded-lg border border-amber-500/5 space-y-2">
                                        <div class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                            <span>Turno:</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                                  :class="activeBarber?.shift === 'noche' ? 'bg-purple-100 text-orange-700' : 'bg-blue-100 text-amber-700'"
                                                  x-text="activeBarber?.shift === 'noche' ? '🌙 Noche' : '☀️ Mañana'">
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-gray-500">
                                            <span class="font-bold">Días laborales:</span>
                                            <div class="flex gap-1 mt-1">
                                                <template x-for="day in ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']">
                                                    <span class="h-5 w-5 rounded-full flex items-center justify-center text-[9px] font-black"
                                                          :class="activeBarber?.[day] ? 'bg-amber-400 text-black font-extrabold' : 'bg-gray-100 text-gray-300'"
                                                          :title="daysOfWeekSpanish[day]"
                                                          x-text="day.substring(0,1).toUpperCase()">
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Select Date & Time slot -->
                                <div class="space-y-4" x-show="selectedBarberId">
                                    <div>
                                        <label for="appointment_date_only" class="block text-sm font-bold text-gray-700 mb-1.5">2. Seleccionar Fecha</label>
                                        <input type="date" name="appointment_date_only" id="appointment_date_only" x-model="selectedDate" min="{{ date('Y-m-d') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                        
                                        <!-- Error message if date falls on barber's day off -->
                                        <div x-show="selectedDate && !isDateValidForBarber" class="mt-2 text-xs font-black text-red-500 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>El barbero seleccionado no labora este día de la semana.</span>
                                        </div>
                                    </div>

                                    <div x-show="selectedDate && isDateValidForBarber">
                                        <label for="appointment_time_slot" class="block text-sm font-bold text-gray-700 mb-1.5">3. Seleccionar Hora de la Cita</label>
                                        <select name="appointment_time_slot" id="appointment_time_slot" x-model="selectedTimeSlot" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                            <option value="">-- Elige la Hora --</option>
                                            <template x-for="slot in timeSlots" :key="slot.value">
                                                <option :value="slot.value" x-text="slot.label"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- DATE MODE VIEW -->
                        <template x-if="filterMode === 'date'">
                            <div class="space-y-4 col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Step 1: Select Date -->
                                <div>
                                    <label for="appointment_date_only_date" class="block text-sm font-bold text-gray-700 mb-1.5">1. Seleccionar Fecha</label>
                                    <input type="date" name="appointment_date_only" id="appointment_date_only_date" x-model="selectedDate" min="{{ date('Y-m-d') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                    
                                    <div x-show="selectedDate" class="mt-2 text-xs font-semibold text-gray-500">
                                        Día seleccionado: <span class="font-bold text-amber-600" x-text="daysOfWeekSpanish[daysOfWeekMap[new Date(selectedDate + 'T00:00:00').getDay()]]"></span>
                                    </div>
                                </div>

                                <!-- Step 2: Select working Barber for that date -->
                                <div class="space-y-4" x-show="selectedDate">
                                    <div>
                                        <label for="barber_id_date" class="block text-sm font-bold text-gray-700 mb-1.5">2. Seleccionar Barbero Disponible</label>
                                        <select name="barber_id" id="barber_id_date" x-model="selectedBarberId" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                            <option value="">-- Elige un Barbero --</option>
                                            <template x-for="barber in barbersWorkingOnSelectedDate" :key="barber.id">
                                                <option :value="barber.id" x-text="barber.name + ' (' + (barber.shift === 'noche' ? '🌙 Noche' : '☀️ Mañana') + ')'"></option>
                                            </template>
                                        </select>
                                        <div x-show="selectedDate && barbersWorkingOnSelectedDate.length === 0" class="mt-2 text-xs font-black text-red-500 flex items-center gap-1">
                                            <span>Ningún barbero labora en este día de la semana. Por favor elige otra fecha.</span>
                                        </div>
                                    </div>

                                    <div x-show="selectedBarberId">
                                        <label for="appointment_time_slot_date" class="block text-sm font-bold text-gray-700 mb-1.5">3. Seleccionar Hora de la Cita</label>
                                        <select name="appointment_time_slot" id="appointment_time_slot_date" x-model="selectedTimeSlot" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-semibold" required>
                                            <option value="">-- Elige la Hora --</option>
                                            <template x-for="slot in timeSlots" :key="slot.value">
                                                <option :value="slot.value" x-text="slot.label"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-amber-500/10">
                        <a href="{{ Auth::user()->role === 'client' ? route('appointments.client_index') : route('appointments.index') }}" class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-xs font-black text-gray-600 uppercase tracking-wider hover:bg-gray-200 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                :disabled="!selectedClientId || !selectedServiceId || !selectedBarberId || !selectedDate || !selectedTimeSlot || (filterMode === 'barber' && !isDateValidForBarber)"
                                class="px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg text-xs font-black text-black uppercase tracking-wider shadow-md hover:from-amber-600 hover:to-orange-600 transition disabled:opacity-25 disabled:cursor-not-allowed">
                            Agendar Cita
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>