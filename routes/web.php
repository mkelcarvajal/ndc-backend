<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'usersession'], function ()
{
    Route::get('/', function(){
        return view('/home');
    });
    Route::get('/home', 'homeController@index')->name('home');
    Route::get('registro', 'capController@index')->name('registro');
    Route::get('prueba1', 'capController@prueba1')->name('prueba1');
    Route::get('prueba2', 'capController@prueba2')->name('prueba2');
    Route::get('prueba3', 'capController@prueba3')->name('prueba3');
    Route::get('correlativo', 'capController@correlativo')->name('correlativo');
    Route::any('pdf_correlativo', 'capController@pdf_correlativo')->name('pdf_correlativo');
    Route::get('desc_certificado', 'capController@desc_certificado')->name('desc_certificado');
    Route::get('desc_diplomas', 'capController@desc_diplomas')->name('desc_diplomas');
    Route::get('rezagados', 'capController@rezagados')->name('rezagados');


    
    Route::post('pdf_diploma', 'capController@pdf_diploma')->name('pdf_diploma');
    Route::post('importarExcel', 'capController@importarExcel')->name('importarExcel');
    Route::post('importarExcel_prueba1', 'capController@importarExcel_prueba1')->name('importarExcel_prueba1');
    Route::post('importarExcel_prueba2', 'capController@importarExcel_prueba2')->name('importarExcel_prueba2');
    Route::post('importarExcel_prueba3', 'capController@importarExcel_prueba3')->name('importarExcel_prueba3');
    Route::post('selectCorrelativo', 'capController@selectCorrelativo')->name('selectCorrelativo');
    Route::post('addCorrelativo', 'capController@addCorrelativo')->name('addCorrelativo');
    Route::post('getInfoRezagados', 'capController@getInfoRezagados')->name('getInfoRezagados');
    Route::post('ActualizarRezagado', 'capController@ActualizarRezagado')->name('ActualizarRezagado');
    Route::post('getBusquedaCorrelativo', 'capController@getBusquedaCorrelativo')->name('getBusquedaCorrelativo');
    Route::post('deleteCorrelativo', 'capController@deleteCorrelativo')->name('deleteCorrelativo');

    

});

Route::post('GetUser', 'homeController@GetUser')->name('GetUser');
Route::get('Salir', 'homeController@Salir')->name('Salir');


Auth::routes();