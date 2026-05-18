<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Listar todos los clientes (incluyendo deshabilitados).
     */
    public function index()
    {
        $clients = User::where('role', 'client')->withTrashed()->get();
        return view('clients.index', compact('clients'));
    }

    /**
     * Formulario para registrar un cliente manualmente.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Guardar el cliente en la base de datos.
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
            'role' => 'client',
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado con éxito.');
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $client = User::where('role', 'client')->withTrashed()->findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Actualizar datos del cliente.
     */
    public function update(Request $request, $id)
    {
        $client = User::where('role', 'client')->withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Perfil de cliente actualizado.');
    }

    /**
     * Deshabilitar temporalmente (Soft Delete).
     */
    public function destroy($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente deshabilitado con éxito.');
    }

    /**
     * Restaurar al cliente (Soft Delete).
     */
    public function restore($id)
    {
        $client = User::where('role', 'client')->onlyTrashed()->findOrFail($id);
        $client->restore();

        return redirect()->route('clients.index')->with('success', 'Cliente reactivado con éxito.');
    }
}