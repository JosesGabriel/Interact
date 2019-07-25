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
    'namespace' => 'Posts',
    'prefix' => 'posts',
], function () {
    Route::get('{id}', 'PostsController@fetch');
    Route::delete('{id}', 'PostsController@delete');
    Route::delete('{id}/{sentiment}/{sentiment_id}', 'PostsController@unsentimentalize')->where('sentiment', 'bear|bull');
    Route::post('/', 'PostsController@create');
    Route::post('{id}/{sentiment}', 'PostsController@sentimentalize')->where('sentiment', 'bear|bull');
    Route::put('{id}', 'PostsController@update');

    Route::group([
        'prefix' => '{post_id}/comments',
        'namespace' => 'Comments',
    ], function () {
        Route::delete('/{id}', 'CommentsController@delete');
        Route::get('/', 'CommentsController@all');
        Route::post('/', 'CommentsController@create');
    });
});
