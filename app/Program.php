<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Program extends Model
{
    const PRICE_TYPE_PER_HOUR = 'per_hour';
    const PRICE_TYPE_TOTAL = 'total';

    protected $fillable = [
        'title',
        'is_retraining',
        'price',
        'price_type',
        'description',
    ];

    public function driving_school(): BelongsTo
    {
        return $this->belongsTo(DrivingSchool::class);
    }

    public function learning_places(): BelongsToMany
    {
        return $this->belongsToMany(LearningPlace::class, 'program_learning_place');
    }

    public function driving_categories() : BelongsToMany
    {
        return $this->belongsToMany(DrivingCategory::class, 'program_driving_category');
    }

    public static function getPriceTypes() : array
    {
        return [
            self::PRICE_TYPE_PER_HOUR => 'В час',
            self::PRICE_TYPE_TOTAL => 'За весь курс',
        ];
    }
}
