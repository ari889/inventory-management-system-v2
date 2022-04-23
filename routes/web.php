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
    Route::get('unauthorized', 'HomeController@unauthorized')->name('unauthorized');

    // menu route
    Route::get('menu', 'MenuController@index')->name('menu');
    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function(){
        Route::post('datatable-data', 'MenuController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MenuController@store_or_update')->name('store.or.update');
        Route::post('edit', 'MenuController@edit')->name('edit');
        Route::post('delete', 'MenuController@delete')->name('delete');
        Route::post('bulk-delete', 'MenuController@bulk_delete')->name('bulk.delete');
        Route::post('order/{menu}', 'MenuController@orderItem')->name('order');

        // Module Routes
        Route::get('builder/{id}', 'ModuleController@index')->name('builder');
        Route::group(['prefix' => 'module', 'as' => 'module.'], function(){
            Route::get('create/{menu}', 'ModuleController@create')->name('create');
            Route::post('store-or-update', 'ModuleController@store_or_update')->name('store.or.update');
            Route::get('{menu}/edit/{module}', 'ModuleController@edit')->name('edit');
            Route::delete('delete/{module}', 'ModuleController@destroy')->name('delete');

            /**
             * module permission routes
             */
            Route::get('permission', 'PermissionController@index')->name('permission');
            Route::group(['prefix' => 'permission', 'as' => 'permission.'], function(){
                Route::post('datatable-data', 'PermissionController@get_datatable_data')->name('datatable.data');
                Route::post('store', 'PermissionController@store')->name('store');
                Route::post('update', 'PermissionController@update')->name('update');
                Route::post('edit', 'PermissionController@edit')->name('edit');
                Route::post('delete', 'PermissionController@delete')->name('delete');
                Route::post('bulk-delete', 'PermissionController@bulk_delete')->name('bulk.delete');
            });
        });
    });
});


