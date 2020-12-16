<?php

/** @var Factory $factory */

use App\Contact;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'contact_type' => array_rand(Contact::getTypes()),
        'value' => function (array $contactData) use ($faker) {
            switch ($contactData['contact_type']) {
                case Contact::TYPE_EMAIL: return $faker->email;
                case Contact::TYPE_WHATSAPP:
                case Contact::TYPE_VIBER:
                case Contact::TYPE_PHONE: return $faker->phoneNumber;
                case Contact::TYPE_TELEGRAM: return '@' . $faker->word;
                case Contact::TYPE_URL: return $faker->url;
            }

            throw new Error('Некорректный тип: ' . $contactData['contact_type']);
        },
    ];
});
