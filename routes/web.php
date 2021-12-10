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

    //REPORTES
    
    Route::get('/indexReportes', 'pruebasController@indexReportes');
    Route::post('personas', 'pruebasController@personas');
    Route::post('respuestas', 'pruebasController@respuestas');
    Route::any('registroPdf', 'pruebasController@registroPdf');
    Route::any('registroExcel', 'pruebasController@registroExcel');

    Route::any('SosiaExcel', 'pruebasController@SosiaExcel');

});


