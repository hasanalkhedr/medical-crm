<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentType extends Model
{
    protected $fillable = ['name', 'default_duration', 'color'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
