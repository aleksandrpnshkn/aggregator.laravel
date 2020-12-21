<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class LearningPlace extends Model
{
    public const TYPE_LEARNING_CLASS = 'learning_class';
    public const TYPE_CLOSED_AREA = 'closed_area';
    public const TYPE_AUTODROME = 'autodrome';

    protected $fillable = [
        'driving_school',
        'type',
        'description',
        'address',
    ];

    protected $appends = [
        'type_label',
    ];

    public function driving_school() : BelongsTo
    {
        return $this->belongsTo(DrivingSchool::class);
    }

    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function programs() : HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function getTypeLabelAttribute() : string
    {
        return $this->getTypeLabel();
    }

    public function getTypeLabel() : string
    {
        return self::getTypes()[$this->type];
    }

    public static function getTypes() : array
    {
        return [
            self::TYPE_LEARNING_CLASS => 'Учебный класс',
            self::TYPE_AUTODROME => 'Автодром',
            self::TYPE_CLOSED_AREA => 'Закрытая площадка',
        ];
    }

    public static function scopeForDrivingSchool(Builder $query, DrivingSchool $drivingSchool) : Builder
    {
        return $query->where('driving_school_id', $drivingSchool->id);
    }
}
