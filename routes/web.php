<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('capacitaciones/{rut}','registroController@capacitaciones')->name('capacitaciones');
Route::any('capacitaciones/{rut}/getDatosCurso','registroController@getDatosCurso')->name('getDatosCurso');
Route::any('capacitaciones/{rut}/pdf_diploma','registroController@pdf_diploma');
