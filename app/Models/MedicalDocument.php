<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalDocument extends Model
{
    protected $fillable = [
        'lead_id', 'visit_id', 'document_type', 'file_path', 'notes'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function visit()
    {
        return $this->belongsTo(PatientVisit::class);
    }
}
