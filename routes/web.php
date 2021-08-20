<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});
Auth::routes();
Route::get('addSocio','socioController@index');
Route::get('listaSocio','socioController@lista_socios');
Route::get('test', function(){
    return now();
});