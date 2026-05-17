<?php

use Illuminate\Support\Facades\Route;

// 1. Ruta de bienvenida pública
Route::get('/', function () {
    return view('welcome');
});

// 2. Grupo de rutas protegidas (Cualquier usuario logueado en Jetstream)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // El Dashboard común que ya viste en pantalla
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ==========================================
    // RUTAS EXCLUSIVAS DEL ADMINISTRADOR
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {
        
        // Rutas especiales para el Soft Delete de Servicios (deben ir antes del resource)
        Route::patch('/services/{id}/restore', [\App\Http\Controllers\ServiceController::class, 'restore'])->name('services.restore');
        
        // CRUD estándar de Servicios (index, create, store, edit, update, destroy)
        Route::resource('services', \App\Http\Controllers\ServiceController::class);
        
        // Aquí meteremos más adelante el Staff y Clientes...
    });

    // ==========================================
    // RUTAS COMPARTIDAS: ADMINISTRADOR Y STAFF (Barberos)
    // ==========================================
    Route::middleware(['role:admin,staff'])->group(function () {
        // Agenda global de citas, control de asistencia, reportes de cortes del día
    });

    // ==========================================
    // RUTAS EXCLUSIVAS DEL CLIENTE
    // ==========================================
    Route::middleware(['role:client'])->group(function () {
        // Mis Citas (Historial del cliente, agendar su propia cita)
    });

});