<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/driving-schools', 'DrivingSchoolController@filter');

Route::get('/driving-categories', 'DrivingCategoryController@getAll');

Route::get('/regions', 'AddressController@getRegions');
Route::get('/regions/{region}/cities', 'AddressController@getCities');
Route::get('/regions/{region}/cities/{city}/districts', 'AddressController@getDistricts')
    ->where('city', '.*');
