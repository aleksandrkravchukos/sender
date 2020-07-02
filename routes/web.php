<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/php', function () {

    if (defined('PDO::ATTR_DRIVER_NAME')) {
        echo 'PDO is available';
    }
    exit;
    phpinfo();
    return '<h1>php works</h1>';
});
