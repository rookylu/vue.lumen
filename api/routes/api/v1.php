<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
$api = app('Dingo\Api\Routing\Router');

// v2 version API
// add in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => [
        'cors',
		//'api.auth',
        //'api.throttle'
    ],
    // each route have a limit of 100 of 1 minutes
    //'limit' => 100, 'expires' => 1
], function ($api) {
    // Auth
    // login
    $api->post('authorizations', [
        'as' => 'authorizations.store',
        'uses' => 'AuthController@store',
    ]);


    // POST
    // post list
    $api->get('posts', [
        'as' => 'posts.index',
        'uses' => 'PostController@index',
    ]);
    // post detail
    $api->get('posts/{id}', [
        'as' => 'posts.show',
        'uses' => 'PostController@show',
    ]);

    // POST COMMENT
    // post comment list
    $api->get('posts/{postId}/comments', [
        'as' => 'posts.comments.index',
        'uses' => 'CommentController@index',
    ]);

    /*
     * 对于authorizations 并没有保存在数据库，所以并没有id，那么对于
     * 刷新（put) 和 删除（delete) 我没有想到更好的命名方式
     * 所以暂时就是 authorizations/current 表示当前header中的这个token。
     * 如果 tokekn 保存保存在数据库，那么就是 authorizations/{id}，像 github 那样。
     */
    $api->put('authorizations/current', [
        'as' => 'authorizations.update',
        'uses' => 'AuthController@update',
    ]);

    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {
        /*
         * 对于authorizations 并没有保存在数据库，所以并没有id，那么对于
         * 刷新（put) 和 删除（delete) 我没有想到更好的命名方式
         * 所以暂时就是 authorizations/current 表示当前header中的这个token。
         * 如果 tokekn 保存保存在数据库，那么就是 authorizations/{id}，像 github 那样。
         */
        $api->delete('authorizations/current', [
            'as' => 'authorizations.destroy',
            'uses' => 'AuthController@destroy',
        ]);

        // USER
        // my detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);

        $api->post('user', [
            'as' => 'user.store',
            'uses' => 'UserController@store',
        ]);

		// user list
		$api->get('users', [
			'as' => 'users.index',
			'uses' => 'UserController@index',
		]);

		// user detail
		$api->get('users/{id}', [
			'as' => 'users.show',
			'uses' => 'UserController@show',
		]);

        // update part of me
        $api->patch('user/{id}', [
            'as' => 'user.update',
            'uses' => 'UserController@update',
        ]);
        // delete user
        $api->delete('user/{id}', [
            'as' => 'user.destroy',
            'uses' => 'UserController@destroy',
        ]);
        // delete user
        $api->delete('users/{id}', [
            'as' => 'users.destroy',
            'uses' => 'UserController@destroy',
        ]);

        // update my password
        $api->put('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);

        // POST
        // user's posts index
        $api->get('user/posts', [
            'as' => 'user.posts.index',
            'uses' => 'PostController@userIndex',
        ]);
        // create a post
        $api->post('posts', [
            'as' => 'posts.store',
            'uses' => 'PostController@store',
        ]);
        // update a post
        $api->put('posts/{id}', [
            'as' => 'posts.update',
            'uses' => 'PostController@update',
        ]);
        // update part of a post
        $api->patch('posts/{id}', [
            'as' => 'posts.patch',
            'uses' => 'PostController@patch',
        ]);
        // delete a post
        $api->delete('posts/{id}', [
            'as' => 'posts.destroy',
            'uses' => 'PostController@destroy',
        ]);

        // POST COMMENT
        // create a comment
        $api->post('posts/{postId}/comments', [
            'as' => 'posts.comments.store',
            'uses' => 'CommentController@store',
        ]);
        $api->put('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.update',
            'uses' => 'CommentController@update',
        ]);
        // delete a comment
        $api->delete('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.destroy',
            'uses' => 'CommentController@destroy',
        ]);


		// menus
        $api->get('menus', [
            'as' => 'menus.list',
            'uses' => 'MenuController@index',
        ]);
        $api->post('menus', [
            'as' => 'menus.store',
            'uses' => 'MenuController@store',
        ]);
		$api->patch('menus/{id}', [
            'as' => 'menus.update',
            'uses' => 'MenuController@update',
        ]);
		$api->delete('menus/{id}', [
            'as' => 'menus.destroy',
            'uses' => 'MenuController@destroy',
        ]);

		// files
        $api->get('folders/{fid}/files', [
            'as' => 'files.list',
            'uses' => 'FileController@index',
        ]);
        $api->post('folders/{fid}/files', [
            'as' => 'files.store',
            'uses' => 'FileController@store',
        ]);
		$api->patch('folders/{fid}/files/{id}', [
            'as' => 'files.update',
            'uses' => 'FileController@update',
        ]);
		$api->delete('folders/{fid}/files/{id}', [
            'as' => 'files.destroy',
            'uses' => 'FileController@destroy',
        ]);

		// folders
        $api->get('folders', [
            'as' => 'folders.list',
            'uses' => 'FolderController@index',
        ]);
        $api->post('folders', [
            'as' => 'folders.store',
            'uses' => 'FolderController@store',
        ]);
		$api->patch('folders/{id}', [
            'as' => 'folders.update',
            'uses' => 'FolderController@update',
        ]);
		$api->delete('folders/{id}', [
            'as' => 'folders.destroy',
            'uses' => 'FolderController@destroy',
        ]);
    });
});
