<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('app');
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
