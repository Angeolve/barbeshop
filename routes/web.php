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
    
    // El Dashboard común
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ==========================================
    // RUTAS EXCLUSIVAS DEL CLIENTE
    // ==========================================
    Route::middleware(['role:client'])->group(function () {
        Route::get('/my-appointments', [\App\Http\Controllers\AppointmentController::class, 'index'])->name('appointments.client_index');
        Route::patch('/appointments/{id}/cancel', [\App\Http\Controllers\AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

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

        // Rutas de Clientes
        Route::patch('/clients/{id}/restore', [\App\Http\Controllers\ClientController::class, 'restore'])->name('clients.restore');
        Route::resource('clients', \App\Http\Controllers\ClientController::class);
    });

    // ==========================================
    // RUTAS TOTALMENTE COMPARTIDAS (Proceso de reserva libre de candados)
    // ==========================================
    Route::get('/appointments/book', [\App\Http\Controllers\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/book', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.store');

    // ==========================================
    // RUTAS DE LA AGENDA MAESTRA (ADMIN Y STAFF)
    // ==========================================
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::get('/appointments', [\App\Http\Controllers\AppointmentController::class, 'index'])->name('appointments.index');
        Route::patch('/appointments/{id}/status', [\App\Http\Controllers\AppointmentController::class, 'updateStatus'])->name('appointments.status');
    });

});