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
    'namespace' => 'Followers',
    'prefix' => 'users',
], function () {
    Route::get('{follow_id}', 'FollowerController@user');
    Route::post('{follow_id}/follow', 'FollowerController@follow');
    Route::delete('{follow_id}/unfollow','FollowerController@unfollow');
});

Route::group([
    'namespace' => 'Posts',
    'prefix' => 'posts',
], function () {
    Route::get('search', 'PostsController@search');
    Route::get('{id}', 'PostsController@fetch');
    Route::delete('{id}', 'PostsController@delete');
    Route::post('/', 'PostsController@create');
    Route::put('{id}', 'PostsController@update');

    Route::group([
        'prefix' => '{id}/attachments',
    ], function () {
        Route::delete('{attachment_id}', 'PostsController@removeAttachment');
        Route::post('/', 'PostsController@addAttachment');
    });

    Route::group([
        'prefix' => '{post_id}/comments',
        'namespace' => 'Comments',
    ], function () {
        Route::delete('/{id}', 'CommentsController@delete');
        Route::get('/', 'CommentsController@all');
        Route::post('/', 'CommentsController@create');
        Route::put('{id}', 'CommentsController@update');

        Route::group([
            'prefix' => '{id}/attachments',
        ], function () {
            Route::delete('{attachment_id}', 'CommentsController@removeAttachment');
            Route::post('/', 'CommentsController@addAttachment');
        });
    });

    Route::group([
        'prefix' => '{id}',
    ], function () {
        Route::delete('un{sentiment}', 'PostsController@unsentimentalize')->where('sentiment', 'bear|bull');
        Route::post('{sentiment}', 'PostsController@sentimentalize')->where('sentiment', 'bear|bull');
    });
});
