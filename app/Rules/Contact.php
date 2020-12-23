<?php

namespace App\Rules;

use App\Contact as ContactModel;
use App\Helpers\PhoneNumber;
use App\Helpers\TelegramUsername;
use Illuminate\Contracts\Validation\Rule;
use Validator;

class Contact implements Rule
{
    private string $message = 'Неизвестный тип контакта.';

    public function passes($attribute, $value)
    {
        switch (request('contact_type')) {
            case ContactModel::TYPE_URL: return $this->validateUrl($value);
            case ContactModel::TYPE_EMAIL: return $this->validateEmail($value);
            case ContactModel::TYPE_PHONE: return $this->validatePhone($value);
            case ContactModel::TYPE_WHATSAPP:
            case ContactModel::TYPE_VIBER: return $this->validateViber($value);
            case ContactModel::TYPE_TELEGRAM: return $this->validateTelegram($value);
        }
        return false;
    }

    public function message()
    {
        return $this->message;
    }

    private function validateUrl(string $value) : bool
    {
        Validator::validate(compact('value'), ['value' => 'url']);
        return true;
    }

    private function validateEmail(string $value) : bool
    {
        Validator::validate(compact('value'), ['value' => 'email']);
        return true;
    }

    private function validatePhone(string $value) : bool
    {
        $this->message = 'Некорректный телефонный номер';
        return PhoneNumber::isRawNumber($value);
    }

    private function validateViber(string $value) : bool
    {
        $this->message = 'Номер должен быть в международном формате';
        return PhoneNumber::isGlobalNumber($value);
    }

    private function validateTelegram(string $value) : bool
    {
        $this->message = 'Некорректный логин';
        return TelegramUsername::isValid($value);
    }
}
