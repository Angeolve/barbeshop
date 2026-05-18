<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                ->orderBy('appointment_date', 'asc')
                ->get();
        } else {
            $appointments = Appointment::with(['service', 'barber', 'client'])
                ->orderBy('appointment_date', 'asc')
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
        $barbers = User::where('role', 'staff')->get();
        
        // Si el usuario es Admin o Staff, cargamos los clientes para que puedan registrar la cita por ellos
        $clients = null;
        if (in_array(Auth::user()->role, ['admin', 'staff'])) {
            $clients = User::where('role', 'client')->get();
        }

        return view('appointments.create', compact('services', 'barbers', 'clients'));
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
            'appointment_date' => 'required|date|after:now',
        ];

        // Si es Admin o Staff, el cliente es obligatorio desde el select del formulario
        if (in_array($user->role, ['admin', 'staff'])) {
            $rules['client_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        Appointment::create([
            // Si es cliente, se asigna su propio ID de sesión; si es Admin/Staff, el que eligieron del select
            'client_id' => $user->role === 'client' ? $user->id : $validated['client_id'],
            'barber_id' => $validated['barber_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $validated['appointment_date'],
            'status' => 'pending',
        ]);

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