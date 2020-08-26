<?php

namespace App\Entities;

use App\Entities\Concerns\HasArea;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Enum\Laravel\HasEnums;

class User extends Authenticatable
{
    use HasEnums, SoftDeletes, HasArea, Notifiable;

    public $incrementing = false;

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
        //
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

    public function hasRole($roleName): bool
    {
        return $this->role === $roleName;
    }

    public function hasPermission($permissionName): bool
    {
        $permissions = $this->getAttribute('permissions');

        if ($permissions === null) {
            return false;
        }

        return in_array($permissionName, $permissions);
    }

    public function assignPermissions(array $permissions)
    {
        $this->setAttribute('permissions', $permissions);
    }
}
