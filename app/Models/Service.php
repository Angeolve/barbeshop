<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- Importante

class Service extends Model
{
    use HasFactory, SoftDeletes; // <-- Activamos Soft Deletes

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_minutes'
    ];

    // Relación: Un servicio puede estar presente en muchas citas
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}