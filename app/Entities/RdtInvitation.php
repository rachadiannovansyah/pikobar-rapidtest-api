<?php

namespace App\Entities;

use App\Enums\LabResultType;
use App\Enums\TestType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Enum\Laravel\HasEnums;

class RdtInvitation extends Model
{
    use HasEnums;

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
}
