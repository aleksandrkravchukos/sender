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

Route::post('/api/addDocument', 'ElasticController@addDocument')->name('addDocument');
Route::post('/api/searchDocument', 'ElasticController@searchDocument')->name('searchDocument');

Route::get('/php', function () {
    return '<h1>php works</h1>';
});
