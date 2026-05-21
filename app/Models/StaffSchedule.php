<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    use HasFactory;

    protected $table = 'staff_schedules';

    protected $fillable = [
        'user_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'shift',
    ];

    protected $casts = [
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    /**
     * Relación con el Barbero (User)
     */
    public function barber()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Comentario: Este modelo representa la disponibilidad semanal de un
    // barbero. Al modificar la estructura (p. ej. añadir rangos de
    // horario por día), crea una nueva migración y adapta los forms y
    // validaciones en `StaffScheduleController`.
}
