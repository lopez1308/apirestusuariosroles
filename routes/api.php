<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('usuarios', 'UsuariosController');
Route::get('obtenerUsuarios', 'UsuariosController@obtenerUsuarios')->name('obtenerUsuarios');
Route::get('obtenerUsuariosRole/{id}', 'UsuariosController@obtenerUsuariosRole')->name('obtenerUsuariosRole');
Route::get('obtenerUsuariosPermiso/{id}', 'UsuariosController@obtenerUsuariosPermiso')->name('obtenerUsuariosPermiso');
Route::get('obtenerUsuariosActivos', 'UsuariosController@obtenerUsuariosActivos')->name('obtenerUsuariosActivos');
Route::get('obtenerUsuariosInactivos', 'UsuariosController@obtenerUsuariosInactivos')->name('obtenerUsuariosInactivos');





