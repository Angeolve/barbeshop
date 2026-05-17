<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Usuario Administrador del sistema
        User::create([
            'name' => 'Admin Barber',
            'email' => 'admin@barber.com',
            'phone' => '9991112233',
            'password' => Hash::make('password'), // La contraseña será 'password'
            'role' => 'admin',
        ]);

        // 2. Crear Usuario Staff (El Barbero estrella)
        User::create([
            'name' => 'Erick MasterFade',
            'email' => 'erick@barber.com',
            'phone' => '9994445566',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // 3. Crear Usuario Cliente de prueba
        User::create([
            'name' => 'Margarita Olvera',
            'email' => 'cliente@gmail.com',
            'phone' => '9997778899',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // 4. Crear un par de Servicios Base para la Barbería
        Service::create([
            'name' => 'Corte de Cabello Premium + Fade',
            'description' => 'Corte estilizado con lavado de cabello, asesoría de imagen y acabado con pomada.',
            'price' => 180.00,
            'duration_minutes' => 30
        ]);

        Service::create([
            'name' => 'Perfilado de Barba con Toalla Caliente',
            'description' => 'Afeitado tradicional con navaja libre, bálsamos, aceites e hidratación profunda.',
            'price' => 120.00,
            'duration_minutes' => 30
        ]);
    }
}