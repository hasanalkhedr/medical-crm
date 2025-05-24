<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// pivot Model
class CustomFieldLead extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}
