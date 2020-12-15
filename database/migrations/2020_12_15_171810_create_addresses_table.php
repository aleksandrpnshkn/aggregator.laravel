<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Подробный адрес понадобится при фильтрации по адресу и поиске автошкол по координатам.
 * Подразумевается использование Dadata.
 */
class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            /*
             * Недостатки строгой привязки адреса к ФИАС и dadata:
             *      - Если привязать, то dadata становится обязательным требованием для сайта.
             *          Придется платить т.к. иначе пользователь не сможет заполнить форму.
             *
             * Недостатки nullable:
             *      - Нужно заботиться об обработке корректности заполнения полей, как отделять типы в таком случае?
             *          Допустим достигнут лимит dadata -> пользователь вбивает как хочет свой город, путает поля.
             *          В итоге в таблицу попадает улица Пушкина... Как потом делать выборку по регионам?
             *
             * Можно исключить ручные адреса из выборки, а пользователю предложить ввести гранулярный адрес завтра.
             * Да, UX ужасный, но в любой момент можно включить платные запросы.
             */
            $table->string('fias_id', 36)->nullable()->unique();

            $table->string('value', 500);

            $table->string('region_with_type', 131)->nullable(); // г Москва
            $table->string('area_with_type', 131)->nullable();
            $table->string('city_with_type', 131)->nullable();
            $table->string('city_district_with_type', 131)->nullable();
            $table->string('settlement_with_type', 131)->nullable();
            $table->string('street_with_type', 131)->nullable();
            $table->string('house', 50)->nullable();

            $table->json('metro')->nullable();

            // spatial index не может быть nullable, а делать отдельную таблицу для точек не хочется, поэтому decimal
            // https://stackoverflow.com/a/12504340
            $table->decimal('geo_lat', 10, 8)->nullable(); // Широта
            $table->decimal('geo_lon', 11, 8)->nullable(); // Долгота

            $table->index('geo_lat');
            $table->index('geo_lon');
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
