<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/driving-schools', 'DrivingSchoolController@index');
Route::middleware('auth')->group(function () {
    Route::get('/driving-schools/create', 'DrivingSchoolController@create');
    Route::post('/driving-schools/', 'DrivingSchoolController@store');
    Route::get('/driving-schools/{slug}/edit', 'DrivingSchoolController@edit');
    Route::patch('/driving-schools/{slug}', 'DrivingSchoolController@update');
    Route::delete('/driving-schools/{slug}', 'DrivingSchoolController@destroy');
});
Route::get('/driving-schools/{slug}', 'DrivingSchoolController@show');

Route::middleware('auth')->group(function () {
    Route::get('/driving-schools/{slug}/learning-places', 'LearningPlaceController@index')
        ->name('learningPlaces');
    Route::get('/driving-schools/{slug}/learning-places/create', 'LearningPlaceController@createOrEdit')
        ->name('createLearningPlace');
    Route::get('/driving-schools/{slug}/learning-places/{learningPlace}/edit', 'LearningPlaceController@createOrEdit')
        ->name('editLearningPlace');
    Route::post('/driving-schools/{slug}/learning-places', 'LearningPlaceController@storeOrUpdate');
    Route::delete('/driving-schools/{slug}/learning-places/{learningPlace}', 'LearningPlaceController@destroy')
        ->name('deleteLearningPlace');
});
