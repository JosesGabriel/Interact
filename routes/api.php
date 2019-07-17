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

Route::group([
    'prefix' => 'posts',
    'namespace' => 'Posts',
], function () {
    Route::post('create', 'PostsController@create');
    Route::post('delete', 'PostsController@delete');
});
