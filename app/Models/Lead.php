<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Lead extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'email',
        'phone_country_code',
        'phone_number',
        'description',
        'emergency_contact_name',
        'emergency_contact_phone',
        'insurance_provider',
        'insurance_policy_number',
        'lead_source_id',
        'pipeline_stage_id',
        'employee_id'
    ];
    protected $casts = [
        'date_of_birth' => 'date'
    ];
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->phone_country_code || !$this->phone_number) {
            return null;
        }

        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $phoneNumber = $phoneUtil->parse($this->phone_number, $this->phone_country_code);
            return $phoneUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
        } catch (\Exception $e) {
            return $this->phone_number; // Fallback to raw number
        }
    }
    public function pipelineStage()
    {
        return $this->belongsTo(PipelineStage::class);
    }
    public function pipelineStageLogs()
    {
        return $this->hasMany(LeadPipelineStage::class);
    }
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function completedTasks()
    {
        return $this->hasMany(Task::class)->where('is_completed', true);
    }

    public function incompleteTasks()
    {
        return $this->hasMany(Task::class)->where('is_completed', false);
    }
    public function customFields()
    {
        return $this->hasMany(CustomFieldLead::class);
    }
    public static function booted(): void
    {
        self::created(function (Lead $lead) {
            $lead->pipelineStageLogs()->create([
                'pipeline_stage_id' => $lead->pipeline_stage_id,
                'user_id' => auth()->check() ? auth()->id() : null
            ]);
        });

        self::updated(function (Lead $lead) {
            $lastLog = $lead->pipelineStageLogs()->whereNotNull('user_id')->latest()->first();

            // Here, we will check if the employee has changed, and if so - add a new log
            // if ($lastLog && $lead->employee_id !== $lastLog?->employee_id) {
            //     $lead->pipelineStageLogs()->create([
            //         'employee_id' => $lead->employee_id,
            //         'notes' => is_null($lead->employee_id) ? 'Employee removed' : '',
            //         'user_id' => auth()->id()
            //     ]);
            // }
        });
    }

    public function medicalProfile()
    {
        return $this->hasOne(PatientMedicalProfile::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
