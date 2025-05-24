<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadPipelineStage extends Model
{
    protected $fillable = ['lead_id', 'pipeline_stage_id', 'user_id', 'notes'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function pipelineStage()
    {
        return $this->belongsTo(PipelineStage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
