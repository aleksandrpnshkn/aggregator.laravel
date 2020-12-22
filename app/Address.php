<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'fias_id',
        'value',
        'region_with_type',
        'area_with_type',
        'city_with_type',
        'city_district_with_type',
        'settlement_with_type',
        'street_with_type',
        'house',
        'metro',
        'geo_lat',
        'geo_lon',
    ];

    protected $casts = [
        'created_at' => 'datetime:c',
        'updated_at' => 'datetime:c',
    ];

    /**
     * В базе не нужны дубли адресов, поэтому при создании проверять существует ли адрес
     */
    public static function getExistedOrCreate(array $addressData) : ?Address
    {
        if (! isset($addressData['value'])) {
            throw new \DomainException('Отсутствует свойство value');
        }

        // Проверить что уже есть такой в базе по полному адресу (адрес может быть в базе без fias_id)
        $address = Address::where('value', '=', $addressData['value'])->first();

        if (! $address) {
            $address = Address::create($addressData);
        }

        if (! $address) {
            throw new \Exception('Не удалось найти/создать адрес');
        }

        return $address;
    }
}
