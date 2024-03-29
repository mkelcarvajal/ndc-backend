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
    Route::any('SosiaPdf/{id}/{select}/{titulo}/{cargo}', 'pruebasController@SosiaPdf');
    Route::any('SosiaInformeResultados', 'pruebasController@SosiaInformeResultados');
    Route::any('SosiaExcel', 'pruebasController@SosiaExcel');


    
    //USUARIOS
    
    Route::get('index', 'usuariosController@index');
    Route::post('modificar_user', 'usuariosController@modificar_user');
    Route::post('agregar_user', 'usuariosController@agregar_user');

    //SOLICITUDES

    Route::get('solicitud','solicitudController@index');
    Route::get('pendientes','solicitudController@pendientes');
    Route::get('sendMail','solicitudController@sendMail');
    Route::post('resendMail','solicitudController@resendMail');
    Route::post('verificarCodigo','solicitudController@verificarCodigo');
    Route::post('insertSolicitud','solicitudController@insertSolicitud');
    Route::post('getProcesosAbiertos','solicitudController@getProcesosAbiertos');
    Route::post('cerrarProceso','solicitudController@cerrarProceso');

    
});


