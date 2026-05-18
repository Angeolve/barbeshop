<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StaffSchedule;
use Illuminate\Http\Request;

class StaffScheduleController extends Controller
{
    /**
     * Mostrar los horarios de todos los barberos.
     */
    public function index()
    {
        $barbers = User::where('role', 'staff')->withTrashed()->get();

        // Asegurar que cada barbero tenga un horario por defecto
        foreach ($barbers as $barber) {
            if (!$barber->schedule) {
                StaffSchedule::create([
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

        // Volver a cargar con la relación
        $barbers = User::where('role', 'staff')->with('schedule')->get();

        return view('schedules.index', compact('barbers'));
    }

    /**
     * Actualizar el horario de un barbero.
     */
    public function update(Request $request, $id)
    {
        $schedule = StaffSchedule::where('user_id', $id)->firstOrFail();

        $validated = $request->validate([
            'shift' => 'required|in:mañana,noche',
            'monday' => 'nullable|boolean',
            'tuesday' => 'nullable|boolean',
            'wednesday' => 'nullable|boolean',
            'thursday' => 'nullable|boolean',
            'friday' => 'nullable|boolean',
            'saturday' => 'nullable|boolean',
            'sunday' => 'nullable|boolean',
        ]);

        $schedule->update([
            'shift' => $validated['shift'],
            'monday' => $request->has('monday'),
            'tuesday' => $request->has('tuesday'),
            'wednesday' => $request->has('wednesday'),
            'thursday' => $request->has('thursday'),
            'friday' => $request->has('friday'),
            'saturday' => $request->has('saturday'),
            'sunday' => $request->has('sunday'),
        ]);

        return redirect()->route('schedules.index')->with('success', 'Horario de barbero actualizado con éxito.');
    }
}
