<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisit extends Model
{
    protected $fillable = [
        'lead_id', 'appointment_id', 'provider_id',
        'visit_date', 'diagnosis', 'treatment_plan', 'follow_up_date'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function services()
    {
        return $this->belongsToMany(MedicalService::class, 'visit_services')
            ->withPivot('quantity', 'notes');
    }

    public function documents()
    {
        return $this->hasMany(MedicalDocument::class);
    }
}
