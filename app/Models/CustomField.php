<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = ['name'];

    public function leads()
    {
        return $this->belongsToMany(Lead::class)->withPivot('value');
    }
}
