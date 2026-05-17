<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Mostrar el listado de servicios (incluyendo opción de ver eliminados).
     */
    public function index()
    {
        // Traemos los servicios activos y también los que sufrieron Soft Delete
        $services = Service::withTrashed()->get();
        return view('services.index', compact('services'));
    }

    /**
     * Mostrar el formulario para crear un nuevo servicio.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Guardar un servicio en la base de datos (Validación Estricta Backend).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:services,name',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:10|max:240',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Servicio creado con éxito.');
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit($id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        return view('services.edit', compact('service'));
    }

    /**
     * Actualizar el servicio (Validación Estricta Backend).
     */
    public function update(Request $request, $id)
    {
        $service = Service::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:services,name,' . $service->id,
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:10|max:240',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Servicio actualizado con éxito.');
    }

    /**
     * Eliminar temporalmente un servicio (Soft Delete).
     */
    public function destroy(Service $service)
    {
        $service->delete(); // Laravel cambia automáticamente el valor de deleted_at

        return redirect()->route('services.index')->with('success', 'Servicio deshabilitado con éxito.');
    }

    /**
     * Restaurar un servicio eliminado mediante Soft Delete.
     */
    public function restore($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();

        return redirect()->route('services.index')->with('success', 'Servicio restaurado con éxito.');
    }
}