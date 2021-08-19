<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('layouts.app');
});

Route::get('addSocio','socioController@index');
Route::get('listaSocio','socioController@lista_socios');
