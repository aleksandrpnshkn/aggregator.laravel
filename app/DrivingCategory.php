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

    public function conclusions() : BelongsToMany
    {
        return $this->belongsToMany(Conclusion::class, 'conclusion_driving_category');
    }

    public function programs() : BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_driving_category');
    }
}
