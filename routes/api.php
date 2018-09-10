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


Route::get('/sabores', 'PedidoController@getSabores');

Route::get('/tamanhos', 'PedidoController@getTamanhos');

Route::get('/personalizacao', 'PedidoController@getPersonalizacao');

Route::get('/pedido/{id}', 'PedidoController@getPedidoMontadoPorId');

Route::any('/pedido', 'PedidoController@montarPedido');
