<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicalProfile extends Model
{
    protected $fillable = [
        'lead_id', 'blood_type', 'height', 'weight',
        'known_allergies', 'chronic_conditions', 'current_medications'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
