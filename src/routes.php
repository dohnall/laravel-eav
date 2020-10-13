<?php
use Illuminate\Support\Facades\Route;

Route::prefix('eav')->namespace('Dohnall\LaravelEav\Controllers')->group(function() {
    Route::post('/entity/', 'EavEntityController@store')->name('eav.entity.store');
    Route::patch('/entity/{entity}', 'EavEntityController@update')->name('eav.entity.update');
    Route::delete('/entity/{entity}', 'EavEntityController@destroy')->name('eav.entity.delete');

    Route::post('/attribute/', 'EavAttributeController@store')->name('eav.attribute.store');
    Route::patch('/attribute/{attribute}', 'EavAttributeController@update')->name('eav.attribute.update');
    Route::delete('/attribute/{attribute}', 'EavAttributeController@destroy')->name('eav.attribute.delete');
});
