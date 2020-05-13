<?php

namespace App\Entities;

use App\Enums\RdtEventStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property \Carbon\Carbon $start_at
 * @property \Carbon\Carbon $end_at
 * @property string $event_code
 */
class RdtEvent extends Model
{
    use HasEnums, SoftDeletes;

    protected $enums = [
        'status' => RdtEventStatus::class,
    ];

    public function applicants()
    {
        return $this->hasMany(RdtApplicant::class, 'rdt_event_id');
    }
}
