<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PipelineStage extends Model
{
    protected $fillable = ['name', 'position', 'is_default', 'is_clinical'];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
