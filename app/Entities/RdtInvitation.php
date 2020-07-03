<?php

namespace App\Entities;

use App\Enums\LabResultType;
use App\Enums\TestType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property string $registration_code
 * @property \App\Entities\RdtApplicant $applicant
 * @property \Illuminate\Support\Carbon attended_at
 */
class RdtInvitation extends Model
{
    use HasEnums;

    protected $fillable = ['rdt_applicant_id'];

    protected $enums = [
        'lab_result_type' => LabResultType::class.':nullable',
        'test_type' => TestType::class.':nullable',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'attended_at',
        'notified_at',
        'confirmed_at',
        'result_at',
    ];

    public function applicant()
    {
        return $this->belongsTo(RdtApplicant::class, 'rdt_applicant_id');
    }

    public function event()
    {
        return $this->belongsTo(RdtEvent::class, 'rdt_event_id');
    }

    public function schedule()
    {
        return $this->belongsTo(RdtEventSchedule::class, 'rdt_event_schedule_id');
    }
}
