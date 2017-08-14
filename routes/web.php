<?php
Route::get('/', function () {
    return view('pages.home');
});

Route::get('commercial_performance', 'FacturaController@index')->name('commercial_performance');
Route::post('commercial_performance', 'FacturaController@index')->name('commercial_performance');