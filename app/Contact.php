<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contact extends Model
{
    const TYPE_URL = 'url';
    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';
    const TYPE_TELEGRAM = 'tg';
    const TYPE_VIBER = 'viber';
    const TYPE_WHATSAPP = 'whatsapp';

    protected $fillable = [
        'contact_type',
        'value',
    ];

    public function driving_schools() : MorphTo
    {
        return $this->morphTo(DrivingSchool::class, 'contactable');
    }

    public function getTypeLabel() : string
    {
        $label = self::getTypes()[$this->contact_type] ?? null;

        if (is_null($label)) {
            throw new \Exception('Некорректное значение');
        }

        return $label;
    }

    public static function getTypes() : array
    {
        return [
            self::TYPE_URL => 'Ссылка',
            self::TYPE_PHONE => 'Телефон',
            self::TYPE_EMAIL => 'Почта',
            self::TYPE_TELEGRAM => 'Telegram',
            self::TYPE_VIBER => 'Viber',
            self::TYPE_WHATSAPP => 'WhatsApp',
        ];
    }
}
