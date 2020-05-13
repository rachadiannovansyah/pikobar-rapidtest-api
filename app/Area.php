<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property              $id
 * @property integer      $parent_id	      nullable
 * @property integer      $depth	          nullable
 * @property string       $name	            nullable,index
 * @property string       $code_bps	        nullable,unique
 * @property string       $code_kemendagri	nullable,unique
 * @property string       $code_dinkes	    nullable,unique
 * @property string       $longitude	      nullable
 * @property string       $latitude	        nullable
 * @property string       $meta	            nullable
 * @property tinyInteger  $status	          nullable
 * @property timestamp    $created_at
 * @property timestamp    $updated_at
 */
class Area extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'depth',
        'name',
        'code_bps',
        'code_kemendagri',
    ];

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Area::class, 'id', 'parent_id');
    }

    public function hospitals()
    {
        $foreignKey = $this->buildForeignKey();

        return $this->hasMany(Hospital::class, $foreignKey, 'code_kemendagri');
    }

    /**
     * Build foreign key name for has many relation (Hospital, User)
     * Example:
     * $area = Area::find(1); // Set Province
     * $area->users // Get all users within Province
     *
     * @return string
     */
    protected function buildForeignKey()
    {
        if ($this->attributes['depth'] === 1) {
            return $foreignKey = 'province_code';
        }

        if ($this->attributes['depth'] === 2) {
            return $foreignKey = 'city_code';
        }

        if ($this->attributes['depth'] === 3) {
            return $foreignKey = 'district_code';
        }

        return $foreignKey = 'village_code';
    }

    public function users()
    {
        $foreignKey = $this->buildForeignKey();

        return $this->hasMany(User::class, $foreignKey, 'code_kemendagri');
    }

    public function medicalCases()
    {
        $foreignKey = $this->buildForeignKey();

        return $this->hasMany(MedicalCase::class, $foreignKey, 'code_kemendagri');
    }
}
