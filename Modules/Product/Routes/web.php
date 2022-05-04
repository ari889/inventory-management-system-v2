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

Route::group(['middleware' => ['auth']], function(){
    Route::get('product', 'ProductController@index')->name('product');
    Route::group(['prefix' => 'product', 'as' => 'product.'], function(){
        Route::post('datatable-data', 'ProductController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ProductController@store_or_update')->name('store.or.update');
        Route::post('edit', 'ProductController@edit')->name('edit');
        Route::post('show', 'ProductController@show')->name('show');
        Route::post('delete', 'ProductController@delete')->name('delete');
        Route::post('bulk-delete', 'ProductController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ProductController@change_status')->name('change.status');
    });

    /**
     * generate 8 digit bar code
     */
    Route::get('generate-code', 'ProductController@generate_code');
    Route::get('populate-unit/{id}', 'ProductController@populate_unit');

    /**
     * print bar code
     */
    Route::get('print-barcode', 'BarcodeCOntroller@index');
    Route::post('generate-barcode', 'BarcodeController@generate_barcode')->name('generate.barcode');
});