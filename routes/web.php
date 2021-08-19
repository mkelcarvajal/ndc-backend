<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('app');
});

Route::get('addSocio','socioController@index');
Route::get('listaSocio','socioController@lista_socios');
