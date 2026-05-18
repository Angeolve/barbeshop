<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- Importante

class Appointment extends Model
{
    use HasFactory, SoftDeletes; // <-- Activamos Soft Deletes

    protected $fillable = [
        'client_id',
        'staff_id',
        'service_id',
        'appointment_time',
        'appointment_date', // Permitir asignación masiva de alias
        'status',
        'notes'
    ];

    // Relación: La cita pertenece a un Cliente (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

    // Relación: La cita pertenece a un Barbero/Staff (User)
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id')->withTrashed();
    }

    // Alias de relación para compatibilidad con código existente
    public function barber()
    {
        return $this->belongsTo(User::class, 'staff_id')->withTrashed();
    }

    // Relación: La cita incluye un Servicio específico
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->withTrashed();
    }

    // Accessor para obtener la fecha de la cita usando el nombre viejo
    public function getAppointmentDateAttribute()
    {
        return $this->appointment_time;
    }

    // Mutator para guardar la fecha de la cita usando el nombre viejo
    public function setAppointmentDateAttribute($value)
    {
        $this->attributes['appointment_time'] = $value;
    }
}