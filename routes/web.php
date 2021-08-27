<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function(){
    return now();
});
Route::get('/', function(){
    return view('/home');
});
Route::get('/home', 'homeController@index')->name('home');
Route::get('addSocio','socioController@index')->name('addSocio');
Route::get('listaSocio','socioController@lista_socios')->name('listaSocio');
Route::post('insSocio','socioController@insSocio')->name('insSocio');

Auth::routes();