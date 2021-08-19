<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('app');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('addSocio','socioController@index');
Route::get('listaSocio','socioController@lista_socios');