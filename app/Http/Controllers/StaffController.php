<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Listar solo el personal de la barbería (incluyendo deshabilitados).
     */
    public function index()
    {
        $staffMembers = User::where('role', 'staff')->withTrashed()->get();
        return view('staff.index', compact('staffMembers'));
    }

    /**
     * Formulario para registrar un nuevo barbero.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Guardar el registro en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff', // Asignación automática de rol
        ]);

        return redirect()->route('staff.index')->with('success', 'Barbero registrado con éxito.');
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $barber = User::where('role', 'staff')->withTrashed()->findOrFail($id);
        return view('staff.edit', compact('barber')); // <-- Paréntesis corregido aquí
    }

    /**
     * Actualizar los datos del barbero.
     */
    public function update(Request $request, $id)
    {
        $barber = User::where('role', 'staff')->withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $barber->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $barber->update($validated);

        return redirect()->route('staff.index')->with('success', 'Datos del barbero actualizados.');
    }

    /**
     * Deshabilitar temporalmente (Soft Delete).
     */
    public function destroy($id)
    {
        $barber = User::where('role', 'staff')->findOrFail($id);
        $barber->delete();

        return redirect()->route('staff.index')->with('success', 'Barbero deshabilitado con éxito.');
    }

    /**
     * Restaurar al barbero (Soft Delete).
     */
    public function restore($id)
    {
        $barber = User::where('role', 'staff')->onlyTrashed()->findOrFail($id);
        $barber->restore();

        return redirect()->route('staff.index')->with('success', 'Barbero reactivado con éxito.');
    }
}