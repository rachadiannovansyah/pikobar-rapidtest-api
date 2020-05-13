<?php

namespace App;

use App\Enums\RdtApplicantStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property string $registration_code
 * @property string $province_code
 * @property int $status
 */
class RdtApplicant extends Model
{
    use HasEnums, SoftDeletes;

    protected $fillable = [
        'nik', 'name', 'address', 'province_code', 'city_code', 'district_code', 'village_code',
        'email', 'phone_number', 'gender', 'birth_date', 'occupation_type', 'occupation_name', 'workplace_name',
        'symptoms', 'symptoms_notes', 'symptoms_interaction', 'symptoms_activity',
        'latitude', 'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'symptoms'          => 'array',
        'symptoms_activity' => 'array',
    ];

    protected $enums = [
        'status' => RdtApplicantStatus::class,
    ];

    public function event()
    {
        return $this->belongsTo(RdtEvent::class, 'rdt_event_id');
    }
}
