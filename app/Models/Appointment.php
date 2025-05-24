<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'lead_id', 'provider_id', 'appointment_type_id',
        'scheduled_at', 'duration', 'status', 'notes'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function type()
    {
        return $this->belongsTo(AppointmentType::class, 'appointment_type_id');
    }

    public function visit()
    {
        return $this->hasOne(PatientVisit::class);
    }
}
