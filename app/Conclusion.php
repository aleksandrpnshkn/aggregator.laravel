<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conclusion extends Model
{
    public function driving_categories() : BelongsToMany
    {
        return $this->belongsToMany(DrivingCategory::class, 'conclusion_driving_category');
    }

    public function conclusion_results() : HasMany
    {
        return $this->hasMany(ConclusionResult::class);
    }

    public function verified_by_user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function driving_school() : BelongsTo
    {
        return $this->belongsTo(DrivingSchool::class);
    }
}
