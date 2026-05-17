<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- 1. Importamos SoftDeletes
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes; // <-- 2. Usamos SoftDeletes dentro de la clase

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone', // <-- 3. Agregamos el teléfono para WhatsApp
        'role',  // <-- 4. Agregamos el rol (admin, staff, client)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // <-- 5. Relación: Si el usuario es un CLIENTE, tiene muchas citas
    public function customerAppointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    // <-- 6. Relación: Si el usuario es STAFF (Barbero), tiene muchas citas asignadas
    public function staffAppointments()
    {
        return $this->hasMany(Appointment::class, 'staff_id');
    }
}