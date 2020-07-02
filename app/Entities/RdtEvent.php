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
 * @property \App\Entities\RdtEventSchedule[] $schedules
 * @property \App\Entities\RdtInvitation[] $invitations
 */
class RdtEvent extends Model
{
    use HasEnums, SoftDeletes;

    protected $enums = [
        'status' => RdtEventStatus::class,
    ];

    protected $fillable = [
        'event_name',
        'event_location',
        'start_at',
        'end_at',
        'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_at',
        'end_at',
    ];

    public function city()
    {
        return $this->belongsTo(Area::class, 'city_code', 'code_kemendagri');
    }

    public function schedules()
    {
        return $this->hasMany(RdtEventSchedule::class, 'rdt_event_id');
    }

    public function invitations()
    {
        return $this->hasMany(RdtInvitation::class, 'rdt_event_id');
    }
}
