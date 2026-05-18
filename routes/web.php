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
        
        // Rutas de Servicios
        Route::patch('/services/{id}/restore', [\App\Http\Controllers\ServiceController::class, 'restore'])->name('services.restore');
        Route::resource('services', \App\Http\Controllers\ServiceController::class);
        
        // Rutas de Staff (Barberos)
        Route::patch('/staff/{id}/restore', [\App\Http\Controllers\StaffController::class, 'restore'])->name('staff.restore');
        Route::resource('staff', \App\Http\Controllers\StaffController::class);

        // <-- AGREGAR ESTAS LÍNEAS AQUÍ PARA LOS CLIENTES -->
        Route::patch('/clients/{id}/restore', [\App\Http\Controllers\ClientController::class, 'restore'])->name('clients.restore');
        Route::resource('clients', \App\Http\Controllers\ClientController::class);
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