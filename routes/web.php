<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('layouts.app');
});
Auth::routes();
Route::get('addSocio','socioController@index');
Route::get('listaSocio','socioController@lista_socios');