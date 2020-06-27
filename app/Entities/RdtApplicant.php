<?php

namespace App\Entities;

use App\Entities\Concerns\HashidsRoutable;
use App\Enums\RdtApplicantStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Spatie\Enum\Laravel\HasEnums;
use UrlSigner;

/**
 * @property string $registration_code
 * @property string $province_code
 * @property int $status
 */
class RdtApplicant extends Model
{
    use HashidsRoutable, HasEnums, SoftDeletes;

    protected $fillable = [
        'nik', 'name', 'address', 'province_code', 'city_code', 'district_code', 'village_code',
        'email', 'phone_number', 'gender', 'birth_date', 'occupation_type', 'occupation_name', 'workplace_name',
        'symptoms', 'symptoms_notes', 'symptoms_interaction', 'symptoms_activity',
        'latitude', 'longitude', 'pikobar_session_id', 'pikobar_user_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birth_date',
        'approved_at',
        'invited_at',
        'attended_at',
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

    public function city()
    {
        return $this->belongsTo(Area::class, 'city_code', 'code_kemendagri');
    }

    public function district()
    {
        return $this->belongsTo(Area::class, 'district_code', 'code_kemendagri');
    }

    public function village()
    {
        return $this->belongsTo(Area::class, 'village_code', 'code_kemendagri');
    }

    public function invitations()
    {
        return $this->hasMany(RdtInvitation::class, 'rdt_applicant_id');
    }

    public function getQrCodeUrlAttribute()
    {
        $url = URL::route(
            'registration.qrcode',
            ['registration_code' => $this->attributes['registration_code']]
        );

        return UrlSigner::sign($url);
    }
}
