<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DrivingCategory extends Model
{
    public function driving_schools() : BelongsToMany
    {
        return $this->belongsToMany(DrivingSchool::class, 'driving_school_driving_category');
    }
}
