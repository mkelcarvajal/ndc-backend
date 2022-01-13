<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'usersession'], function ()
{
    Route::get('/', function(){
        return view('/home');
    });
    Route::get('/home', 'homeController@index')->name('home');
    Route::get('/indexMedico', 'medicoController@indexMedico')->name('indexMedico');
    Route::post('infoPiso', 'medicoController@infoPiso')->name('infoPiso');
    Route::get('reportes', 'medicoController@reportes')->name('reportes');
    Route::post('pdf', 'medicoController@pdf')->name('pdf');

});

Route::post('GetUser', 'homeController@GetUser')->name('GetUser');
Route::get('Salir', 'homeController@Salir')->name('Salir');


Auth::routes();