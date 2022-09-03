<?php

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



	

Route::group(['middleware' => 'auth'], function () {

	Route::get('/', 'HomeController@index');
	Route::get('/home', 'HomeController@index');
	Route::get('/micuenta', 'HomeController@miCuenta');
	Route::get('/crear-campania', 'ListasController@crearCampania');
	Route::post('/generar-listas', 'ListasController@generarListas');
	Route::post('/generar-instancias', 'ListasController@generarInstancias');
	Route::get('/ver-listas/{encabezado_de_envio_id}', 'ListasController@verListas');
	


	// RUTAS GENERICAS
	Route::post('/crearlista', 'GenericController@crearLista');
	Route::post('/crearabm', 'GenericController@crearABM');
	Route::post('/enviarabm/{gen_modelo}', 'GenericController@crearABM');
	Route::post('/store', 'GenericController@store');
	Route::get('/list/{gen_modelo}/{gen_opcion}', 'GenericController@index');
	// FIN RUTAS GENERICAS


	Route::get('/le/{lista_de_envio_id}/{hash}', 'ListasController@listEnvios');
	Route::post('/le/registrar-envio/{codigo_de_envio_id}/{inscripcion_id}/{medio_de_envio_id}', 'ListasController@registrarEnvio');
	Route::post('/le/setear-sino/{codigo}/{contacto_id}/{tipo_de_lista_de_envio_id}', 'ListasController@setearSino');
	Route::get('/crearlistas/{cantidad}/{tipo_de_lista_de_envio_id}', 'ListasController@crearListas');


});

Route::get('/prueba', function () {
    return view('prueba');
});	


Auth::routes();

Route::get('/delcache', function () {
    $exitCode = Artisan::call('cache:clear');
    echo 'Cache Borrada';
});



//FORMS
