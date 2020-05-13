<?php

namespace App;

use App\Entities\Concerns\HasArea;
use App\Enums\UserStatus;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Enum\Laravel\HasEnums;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

/*
 * @property bigIncrements	$id
 * @property string	        $name
 * @property string	        $email              unique
 * @property timestamp	    $email_verified_at	nullable
 * @property string	        $password	          nullable
 * @property string	        $province_code	    nullable
 * @property string	        $city_code	        nullable
 * @property string	        $district_code	    nullable
 * @property string	        $village_code	      nullable
 * @property tinyInteger	  $status	            nullable
 * @property string         $remember_token     Used for 'Remember Me' feature
 * @property timestamp      $created_at
 * @property timestamp      $updated_at
 * @property timestamp      $deleted_at         Used for soft delete feature
 */
class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
    use HasEnums, SoftDeletes, HasRoles, HasArea, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    protected $guard_name = 'api';

    protected $enums = [
        'status' => UserStatus::class,
    ];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=200&d=mm';
    }

    /**
     * Get the oauth providers.
     *
     * @return HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
