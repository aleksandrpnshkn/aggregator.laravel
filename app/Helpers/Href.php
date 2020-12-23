<?php

namespace App\Helpers;

/**
 * Хелпер для получения href для ссылок
 * @package Aleksandrkrzhn\Aggregator\Classes
 */
class Href
{
    public static function forPhone(string $phoneNumber) : string
    {
        // Номер телефона может содержать + и -, без пробелов и скобок https://tools.ietf.org/html/rfc3966
        return 'tel:' . self::clean(PhoneNumber::cleanNumber($phoneNumber));
    }

    public static function forEmail(string $email) : string
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException('Некорректный email');
        }

        return 'mailto:' . self::clean($email);
    }

    public static function forViber(string $phoneNumber) : string
    {
        if (! PhoneNumber::isGlobalNumber($phoneNumber)) {
            throw new \DomainException('Номер должен быть в международном формате: ' . $phoneNumber);
        }

        // + заменить на %2B
        return 'href="viber://chat?number=' . self::clean(rawurlencode(PhoneNumber::cleanNumber($phoneNumber)));
    }

    public static function forWhatsapp(string $phoneNumber) : string
    {
        if (! PhoneNumber::isGlobalNumber($phoneNumber)) {
            throw new \DomainException('Номер должен быть в международном формате: ' . $phoneNumber);
        }

        return 'https://wa.me/' . self::clean(ltrim(PhoneNumber::cleanNumber($phoneNumber), '+'));
    }


    public static function forTelegram(string $telegramUsername) : string
    {
        return 'https://t.me/' . self::clean(TelegramUsername::withoutAtSymbol($telegramUsername));
    }

    public static function clean(string $href) : string
    {
        return htmlentities(strip_tags($href));
    }
}
