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
    Route::post('/', 'PostsController@create');
    Route::delete('{id}', 'PostsController@delete');
    Route::post('fetch', 'PostsController@fetch');
    Route::post('update', 'PostsController@update');

    Route::group([
        'namespace' => 'Comments',
        'prefix' => '{post_id}/comment'
    ], function () {
        Route::post('/', 'CommentsController@create');
        Route::delete('/{id}', 'CommentsController@delete');
        Route::get('/', 'CommentsController@all');
    });
});
