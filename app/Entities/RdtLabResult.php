<?php

namespace App\Entities;

use App\Enums\LabResultType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Enum\Laravel\HasEnums;

class RdtLabResult extends Model
{
    use HasEnums, SoftDeletes;

    protected $enums = [
        'lab_result_type' => LabResultType::class,
    ];

    public function applicant()
    {
        return $this->belongsTo(RdtApplicant::class, 'rdt_applicant_id');
    }
}
