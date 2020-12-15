<?php

namespace Tests\Feature\Models;

use App\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    private array $addressData = [
        'fias_id' => '47ceb433-aeaf-483f-8f7b-ae30e33f90b4',
        'value' => 'г Саратов, ул Авиастроителей',
        'region_with_type' => 'Саратовская обл',
        'area_with_type' => null,
        'city_with_type' => 'г Саратов',
        'city_district_with_type' => 'Заводской р-н',
        'settlement_with_type' => null,
        'street_with_type' => 'ул Авиастроителей',
        'house' => null,
        'metro' => null,
        'geo_lon' => '45.9431228',
        'geo_lat' => '51.5024483',
    ];

    public function testGetExistedAddressOrCreate()
    {
        // Создать если еще нету
        $createdAddress = Address::getExistedOrCreate($this->addressData);
        $this->assertIsInt($createdAddress->id);

        // Вернуть тот же если уже есть с полными данными
        $existedAddress = Address::getExistedOrCreate($this->addressData);
        $this->assertEquals($createdAddress->id, $existedAddress->id);

        // Вернуть тот же если уже есть только с full
        $existedAddress = Address::getExistedOrCreate(['value' => $this->addressData['value']]);
        $this->assertEquals($createdAddress->id, $existedAddress->id);
    }
}
