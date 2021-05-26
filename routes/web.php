<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function ()
{
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', function () {return view('welcome');});

    Route::get('desp-ficha','DespachoController@DespachoIndex')->name('desp-ficha');
    Route::POST('getPacientexFicha','DespachoController@getPacientexFicha');
    Route::POST('/ModificarSolicitud','DespachoController@ModificarSolicitud');

});