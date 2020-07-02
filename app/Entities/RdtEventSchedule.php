<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RdtEventSchedule
 * @package App\Entities
 *
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property \App\Entities\RdtEvent $event
 */
class RdtEventSchedule extends Model
{
    protected $fillable = [
        'start_at',
        'end_at',
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

    public function event()
    {
        return $this->belongsTo(RdtEvent::class, 'rdt_event_id');
    }
}
