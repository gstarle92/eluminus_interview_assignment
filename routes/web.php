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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('status');

//Route::resource('users', 'UsermanagementController');
Route::get('users', ['uses' => 'HomeController@getusers', 'as' => 'users.getuser']);
Route::get('user/add', 'HomeController@add');
Route::post('user/store', 'HomeController@store')->name('store');
Route::get('user/delete/{id}', 'HomeController@destroy');
Route::get('user/edit/{id}', 'HomeController@edit');
Route::post('user/update', 'HomeController@update')->name('update');
