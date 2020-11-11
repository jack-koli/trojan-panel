<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Redis;

if (env('APP_ENV') === 'production') {
    \URL::forceScheme('https');
}

Route::get('/', function () {
    return view('welcome');
});

if (Redis::get('trojan.register') == '1') {
    Auth::routes();
} else {
    Auth::routes([
        'register'=> false,
    ]);
}

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UserController')->except([
    'create', 'store', 'show'
]);

Route::post('/users/toggle','UserController@toggle');
