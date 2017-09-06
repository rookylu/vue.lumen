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
    $api->post('kf/login', [
        'as' => 'authorizations.store',
        'uses' => 'AuthController@store',
    ]);

    /*
     * 对于authorizations 并没有保存在数据库，所以并没有id，那么对于
     * 刷新（put) 和 删除（delete) 我没有想到更好的命名方式
     * 所以暂时就是 authorizations/current 表示当前header中的这个token。
     * 如果 tokekn 保存保存在数据库，那么就是 authorizations/{id}，像 github 那样。
     */
    $api->put('kf/refresh', [
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
        $api->delete('kf/logout', [
            'as' => 'authorizations.destroy',
            'uses' => 'AuthController@destroy',
        ]);

        // USER
        // my detail
        $api->get('kf/info', [
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


        // 庄园主接口
        $api->get('manorOwners', [
            'as' => 'manorOwners.index',
            'uses' => 'ManorOwnerController@index',
        ]);
        $api->post('manorOwners', [
            'as' => 'manorOwners.store',
            'uses' => 'ManorOwnerController@store',
        ]);
        $api->put('manorOwners/{id}', [
            'as' => 'manorOwners.update',
            'uses' => 'ManorOwnerController@update',
        ]);
        $api->delete('manorOwners/{id}', [
            'as' => 'manorOwners.destroy',
            'uses' => 'ManorOwnerController@destroy',
        ]);

        // 签约产品接口
        $api->get('products', [
            'as' => 'products.index',
            'uses' => 'ProductController@index',
        ]);
        $api->post('products', [
            'as' => 'products.store',
            'uses' => 'ProductController@store',
        ]);
        $api->put('products/{id}', [
            'as' => 'products.update',
            'uses' => 'ProductController@update',
        ]);
        $api->delete('products/{id}', [
            'as' => 'products.destroy',
            'uses' => 'ProductController@destroy',
        ]);
    });
});
