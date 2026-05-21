<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Comentarios generales: Este controlador maneja el CRUD de clientes
     * que en la aplicación están representados por el modelo `User` con
     * `role = 'client'`. Se utilizan `withTrashed()` y `onlyTrashed()` para
     * soportar Soft Deletes. Si cambias campos de la entidad `users`,
     * actualiza aquí las validaciones correspondientes.
     */
    /**
     * Listar todos los clientes (incluyendo deshabilitados).
     */
    public function index()
    {
        // Obtener clientes con soft deleted para mostrar estado en la vista
        $clients = User::where('role', 'client')->withTrashed()->get();
        // Nota: Si la tabla `users` añade campos que deban mostrarse, añadirlos
        // en la vista `clients.index` y garantizar su carga aquí cuando aplique.
        return view('clients.index', compact('clients'));
    }

    /**
     * Formulario para registrar un cliente manualmente.
     */
    public function create()
    {
        // Vista para crear cliente manualmente desde el panel.
        // Guía: Para agregar campos en el formulario, modificar
        // `resources/views/clients/create.blade.php` y actualizar
        // `store()` con validación y mapeo a `User::create([...])`.
        return view('clients.create');
    }

    /**
     * Guardar el cliente en la base de datos.
     */
    public function store(Request $request)
    {
        // Validar entradas del formulario. Si añades un campo nuevo,
        // agrégalo aquí en `$validated` y mapea a `User::create`.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear usuario con rol 'client'. Mantener `role` consistente
        // con la lógica de la aplicación.
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
        // Editar cliente: se carga incluso si está eliminado (trashed)
        $client = User::where('role', 'client')->withTrashed()->findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Actualizar datos del cliente.
     */
    public function update(Request $request, $id)
    {
        // Actualizar datos del cliente: validar y ejecutar update.
        $client = User::where('role', 'client')->withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
        ]);

        // Si añades campos, incluirlos en la validación y el update.
        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Perfil de cliente actualizado.');
    }

    /**
     * Deshabilitar temporalmente (Soft Delete).
     */
    public function destroy($id)
    {
        // Soft delete para deshabilitar al cliente.
        $client = User::where('role', 'client')->findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente deshabilitado con éxito.');
    }

    /**
     * Restaurar al cliente (Soft Delete).
     */
    public function restore($id)
    {
        // Restaurar cliente eliminado (soft delete)
        $client = User::where('role', 'client')->onlyTrashed()->findOrFail($id);
        $client->restore();

        return redirect()->route('clients.index')->with('success', 'Cliente reactivado con éxito.');
    }
}