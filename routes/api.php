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


Route::get('{any?}', function () {
    return response()->json([
        'message' => 'Not found',
        'code' => 404
    ], 404);
})->where('any', '.*');
