<?php

namespace App\Helpers;

use Illuminate\Support\Str;

/*
 * Лучше не делать строгую проверку на красоту ввода для tel:,
 * пусть пользователь форматирует в рамках разрешенных символов как хочет.
 * Главное проверять количество введённых цифр и присутствие неразрешенных символов.
 * Ну и разумеется очищать ввод при выводе.
 *
 * Для мессенджеров надо проверять чтобы ввод был в международном формате (+7 бла-бла).
 * Причем прочие символы удаляются на бэке, а для удобства копипаста и читабельности позволять свободный ввод.
 *
 * Номер РФ:
 * Код страны {1}
 * Код города {3,5} https://na-svyazi.ru/russia_code.htm
 *
 * Номер городского телефона {3,7}
 * Возможно неправильно интерпретирую,
 * но судя по вики в городском номере РФ может быть от 3 (в каком-нибудь мелком поселке) до 7 цифр
 * https://ru.wikipedia.org/wiki/%D0%A2%D0%B5%D0%BB%D0%B5%D1%84%D0%BE%D0%BD%D0%BD%D1%8B%D0%B9_%D0%BF%D0%BB%D0%B0%D0%BD_%D0%BD%D1%83%D0%BC%D0%B5%D1%80%D0%B0%D1%86%D0%B8%D0%B8_%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D0%B8
 *
 * || мобильный номер {7}
 *
 * P.S. https://ru.wikipedia.org/wiki/E.164
 */
class PhoneNumber
{
    const DIGITS_COUNT_MIN = 3;

    // Легкая проверка номера на крайние допустимые значения
    const RAW_NUMBER_REGEXP = '/^\+?[0-9\-() ]{' . self::DIGITS_COUNT_MIN . ',30}$/';

    // Любые символы кроме цифр и +
    const FORMATTING_SYMBOLS_REGEXP = '/[^+0-9]/';

    public string $value;

    /**
     * Получить номер телефона без скобок, пробелов и дефиса. Просто + и цифры
     */
    public static function cleanNumber(string $phoneNumber) : string
    {
        return preg_replace(self::FORMATTING_SYMBOLS_REGEXP, '', $phoneNumber);
    }

    /**
     * Номер телефона в свободном формате. Может включать пробелы, -, (, )
     */
    public static function isRawNumber(string $phoneNumber) : bool
    {
        $onlyDigits = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strlen($onlyDigits) < self::DIGITS_COUNT_MIN) {
            return false;
        }

        return (bool)preg_match(self::RAW_NUMBER_REGEXP, $phoneNumber);
    }

    /**
     * Номер телефона в международном формате
     */
    public static function isGlobalNumber(string $phoneNumber) : bool
    {
        return self::isRawNumber($phoneNumber)
            && Str::startsWith(self::cleanNumber($phoneNumber), '+7');
    }
}
