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

Auth::routes(['verify' => true]);

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
Route::get('/driving-schools/{slug}/learning-places/{learningPlace}', 'LearningPlaceController@show')
    ->name('showLearningPlace');

Route::middleware('auth')->group(function () {
    Route::get('/driving-schools/{slug}/programs', 'ProgramController@index')->name('programs');
    Route::get('/driving-schools/{slug}/programs/create', 'ProgramController@createOrEdit')->name('createProgram');
    Route::get('/driving-schools/{slug}/programs/{program}/edit', 'ProgramController@createOrEdit')->name('editProgram');
    Route::post('/driving-schools/{slug}/programs', 'ProgramController@storeOrUpdate');
    Route::delete('/driving-schools/{slug}/programs/{program}', 'ProgramController@destroy')->name('deleteProgram');
});
Route::get('/driving-schools/{slug}/programs/{program}', 'ProgramController@show')->name('showProgram');
