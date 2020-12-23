<?php

namespace App;

use App\Helpers\Href;
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

    protected $casts = [
        'created_at' => 'datetime:c',
        'updated_at' => 'datetime:c',
    ];

    protected $appends = [
        'href',
        'contact_type_label',
        'contact_type_icon',
    ];

    public function contactable() : MorphTo
    {
        return $this->morphTo();
    }

    public function getHrefAttribute() : ?string
    {
        $href = null;

        switch ($this->contact_type) {
            case self::TYPE_URL: $href = $this->value; break;
            case self::TYPE_WHATSAPP: $href = Href::forWhatsapp($this->value); break;
            case self::TYPE_VIBER: $href = Href::forViber($this->value); break;
            case self::TYPE_TELEGRAM: $href = Href::forTelegram($this->value); break;
            case self::TYPE_EMAIL: $href = Href::forEmail($this->value); break;
            case self::TYPE_PHONE: $href = Href::forPhone($this->value); break;
        }

        return $href;
    }

    public function getContactTypeIconAttribute() : ?string
    {
        switch ($this->contact_type) {
            case self::TYPE_URL: return '<i class="fas fa-external-link-alt"></i>';
            case self::TYPE_WHATSAPP: return '<i class="fab fa-whatsapp"></i>';
            case self::TYPE_VIBER: return '<i class="fab fa-viber"></i>';
            case self::TYPE_TELEGRAM: return '<i class="fab fa-telegram-plane"></i>';
            case self::TYPE_EMAIL: return'<i class="fas fa-at"></i>';
            case self::TYPE_PHONE: return '<i class="fas fa-phone"></i>';
        }

        return null;
    }

    public function getContactTypeLabelAttribute() : string
    {
        return $this->getTypeLabel();
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
