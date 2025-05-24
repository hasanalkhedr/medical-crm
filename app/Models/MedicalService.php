<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalService extends Model
{
    protected $fillable = ['name', 'description', 'duration', 'price'];

    public function visits()
    {
        return $this->belongsToMany(PatientVisit::class, 'visit_services')
            ->withPivot('quantity', 'notes');
    }
}
