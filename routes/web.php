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

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function(){
    // dashboard route
    Route::get('/', 'HomeController@index')->name('dashboard');

    // menu route
    Route::get('menu', 'MenuController@index')->name('menu');
    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function(){
        Route::post('datatable-data', 'MenuController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MenuController@store_or_update')->name('store.or.update');
        Route::post('edit', 'MenuController@edit')->name('edit');
        Route::post('delete', 'MenuController@delete')->name('delete');
        Route::post('bulk-delete', 'MenuController@bulk_delete')->name('bulk.delete');
        Route::post('order/{menu}', 'MenuController@orderItem')->name('order');
    });
});


