<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function ()
{
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/', function () {return view('welcome');});
Route::get('/indexPrueba', 'pruebasController@indexPrueba');
Route::post('preguntas', 'pruebasController@preguntas');
Route::post('registrarPreguntasElectrica', 'pruebasController@registrarPreguntasElectrica');
Route::get('registroPdf', 'pruebasController@registroPdf');


});

