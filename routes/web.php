<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});
Route::get('/home', 'homeController@index')->name('home');
Route::get('addSocio','socioController@index');
Route::get('listaSocio','socioController@lista_socios');
