<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DrivingSchool extends Model
{
    public const POST_STATUS_DRAFT = 'draft';
    public const POST_STATUS_PUBLISH = 'publish';

    public const SCHOOL_STATUS_CLOSED = 'is_closed';
    public const SCHOOL_STATUS_OPEN = 'is_open';
    public const SCHOOL_STATUS_OPEN_SOON = 'is_open_soon';

    public const TYPE_STATE = 'state';
    public const TYPE_NON_STATE = 'non-state';

    protected $fillable = [
        'name',
        'legal_name',
        'inn',
        'address',
        'post_status',
        'slug',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'open_date',
        'close_date',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function learning_places() : HasMany
    {
        return $this->hasMany(LearningPlace::class);
    }

    public function driving_categories() : BelongsToMany
    {
        return $this->belongsToMany(DrivingCategory::class, 'driving_school_driving_category');
    }

    public function conclusions() : HasMany
    {
        return $this->hasMany(Conclusion::class);
    }

    public function programs() : HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function getPostStatusLabel() : string
    {
        if (!in_array($this->post_status, array_keys(self::getPostStatuses()), true)) {
            throw new \Exception('Такой статус поста не существует');
        }

        return self::getPostStatuses()[$this->post_status];
    }

    public static function getSchoolStatuses() : array
    {
        return [
            self::SCHOOL_STATUS_OPEN => 'Работает',
            self::SCHOOL_STATUS_CLOSED => 'Не работает',
            self::SCHOOL_STATUS_OPEN_SOON => 'Скоро открывается',
        ];
    }

    public static function getPostStatuses() : array
    {
        return [
            self::POST_STATUS_DRAFT => 'Черновик',
            self::POST_STATUS_PUBLISH => 'Опубликовано',
        ];
    }

    public static function getTypes() : array
    {
        return [
            self::TYPE_NON_STATE => 'Частная',
            self::TYPE_STATE => 'Государственная',
        ];
    }
}
