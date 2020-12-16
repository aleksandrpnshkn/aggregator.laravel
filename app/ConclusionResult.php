<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConclusionResult extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'starts_at',
        'ends_at',
    ];

    public function conclusion() : BelongsTo
    {
        return $this->belongsTo(Conclusion::class);
    }

    public function learning_place() : BelongsTo
    {
        return $this->belongsTo(LearningPlace::class);
    }

    public function isExpired() : bool
    {
        if (
            $this->starts_at
            && ! $this->ends_at
        ) {
            return false; // Бессрочное заключение
        }

        return $this->ends_at->isBefore(Carbon::now());
    }
}
