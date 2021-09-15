<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function(){
    return now();
});
Route::get('/', function(){
    return view('/home');
});
Route::get('/home', 'homeController@index')->name('home');
//SOciOS
Route::get('addSocio','socioController@index')->name('addSocio');
Route::get('listaSocio','socioController@lista_socios')->name('listaSocio');
Route::post('insSocio','socioController@insSocio')->name('insSocio');
Route::post('updSocio','socioController@updSocio')->name('updSocio');
Route::post('delSocio','socioController@delSocio')->name('delSocio');

//BALANCE
Route::get('index_balance','balanceController@index_balance')->name('index_balance');
Route::get('addBalance','balanceController@addBalance')->name('addBalance');
Route::post('insBalance','balanceController@insBalance')->name('insBalance');
Route::post('updBalance','balanceController@updBalance')->name('updBalance');


Auth::routes();