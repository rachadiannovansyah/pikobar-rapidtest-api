<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class RdtSurvey extends Model
{
    public function applicant()
    {
        return $this->belongsTo(RdtApplicant::class, 'rdt_applicant_id');
    }
}
