<?php

use Illuminate\Support\Facades\Route;

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

Route::post('login','AuthController@login');
Route::get('me','AuthController@me');
Route::post('logout','AuthController@logout');

Route::get('/accounts','AccountsController@index');
Route::get('/accounts/{account}','AccountsController@show');
Route::post('/accounts','AccountsController@store');
Route::put('/accounts/{account}','AccountsController@update');
Route::patch('/accounts/{account}/deactivate','AccountsController@deactivate');
Route::patch('/accounts/{account}/activate','AccountsController@activate');


Route::get('{any?}', function () {
    return response()->json([
        'message' => 'Not found',
        'code' => 404
    ], 404);
})->where('any', '.*');
