<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmedMail;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Mostrar la agenda de citas con filtros según el rol.
     */
    public function index()
    {
        $user = Auth::user();

        // Si es cliente, solo ve sus propias citas. Si es admin o staff, ve la agenda completa.
        if ($user->role === 'client') {
            $appointments = Appointment::with(['service', 'barber'])
                ->where('client_id', $user->id)
                ->orderBy('appointment_time', 'asc')
                ->get();
        } else {
            $appointments = Appointment::with(['service', 'barber', 'client'])
                ->orderBy('appointment_time', 'asc')
                ->get();
        }

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Formulario para agendar una nueva cita (Compartido).
     */
    public function create()
    {
        $services = Service::all();

        // Cargar barberos y asegurar que tengan horarios
        $barbers = User::where('role', 'staff')->with('schedule')->get();
        foreach ($barbers as $barber) {
            if (!$barber->schedule) {
                \App\Models\StaffSchedule::create([
                    'user_id' => $barber->id,
                    'monday' => true,
                    'tuesday' => true,
                    'wednesday' => true,
                    'thursday' => true,
                    'friday' => true,
                    'saturday' => true,
                    'sunday' => false,
                    'shift' => 'mañana',
                ]);
            }
        }

        // Volver a consultar con la relación de horarios
        $barbers = User::where('role', 'staff')->with('schedule')->get();

        // Estructura de datos para JavaScript
        $barbersData = $barbers->map(function ($barber) {
            return [
                'id' => $barber->id,
                'name' => $barber->name,
                'shift' => $barber->schedule->shift,
                'monday' => (bool) $barber->schedule->monday,
                'tuesday' => (bool) $barber->schedule->tuesday,
                'wednesday' => (bool) $barber->schedule->wednesday,
                'thursday' => (bool) $barber->schedule->thursday,
                'friday' => (bool) $barber->schedule->friday,
                'saturday' => (bool) $barber->schedule->saturday,
                'sunday' => (bool) $barber->schedule->sunday,
            ];
        });

        // Todos los clientes registrados para selección
        $clients = User::where('role', 'client')->get();

        return view('appointments.create', compact('services', 'barbers', 'clients', 'barbersData'));
    }

    /**
     * Guardar la cita en la base de datos (Validación Backend).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Reglas de validación base
        $rules = [
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:users,id',
            'appointment_date_only' => 'required|date|after_or_equal:today',
            'appointment_time_slot' => 'required|string',
        ];

        // El cliente es obligatorio
        $rules['client_id'] = 'required|exists:users,id';

        $validated = $request->validate($rules);

        // Combinar fecha y hora
        $appointmentTime = $validated['appointment_date_only'] . ' ' . $validated['appointment_time_slot'];

        $appointment = Appointment::create([
            'client_id' => $validated['client_id'],
            'staff_id' => $validated['barber_id'], // Guardar en staff_id (columna de base de datos)
            'service_id' => $validated['service_id'],
            'appointment_time' => $appointmentTime,
            'status' => 'scheduled',
        ]);

        // Intentar enviar el ticket PDF al cliente
        try {
            $appointment->load(['client', 'barber', 'service']);
            if ($appointment->client && $appointment->client->email) {
                Mail::to($appointment->client->email)->send(new AppointmentConfirmedMail($appointment));
            }
        } catch (\Exception $e) {
            Log::error('Error enviando email de confirmación de cita: ' . $e->getMessage());
        }

        // Redirección inteligente dependiendo del rol
        if ($user->role === 'client') {
            return redirect()->route('appointments.client_index')->with('success', 'Tu cita ha sido agendada correctamente.');
        }

        return redirect()->route('appointments.index')->with('success', 'Cita de cliente registrada en la agenda con éxito.');
    }

    /**
     * Cambiar el estado de la cita (Confirmar / Cancelar por Admin o Staff).
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $appointment->update([
            'status' => $validated['status']
        ]);

        return redirect()->route('appointments.index')->with('success', 'Estado de la cita actualizado.');
    }

    /**
     * Cancelar cita por parte del cliente.
     */
    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Seguridad: Un cliente solo puede cancelar su propia cita
        if (Auth::user()->role === 'client' && $appointment->client_id !== Auth::id()) {
            abort(403, 'No tienes autorización para cancelar esta cita.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('appointments.client_index')->with('success', 'Cita cancelada con éxito.');
    }
}