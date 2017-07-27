<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['before'=>'auth'],function(){

	Route::resource('/', 'HomeController');

	Route::get('inventory/supply/rsmi','SupplyInventoryController@rsmi');
	Route::get('inventory/supply/rsmi/{month}','SupplyInventoryController@rsmiPerMonth');
	Route::get('inventory/supply/rsmi/total/bystocknumber/{month}','SupplyInventoryController@rsmiByStockNumber');

	Route::resource('inventory/supply','SupplyInventoryController');

	Route::get('get/supply/{supply}/balance','SupplyInventoryController@getSupplyWithRemainingBalance');

	Route::get('get/inventory/supply/stocknumber/all','StockCardController@getAllStockNumber');

	Route::get('get/inventory/supply/stocknumber','StockCardController@getSupplyStockNumber');

	Route::get('get/office/code/all','OfficeController@getAllCodes');

	Route::get('get/supply/inventory/stockcard/months/all','SupplyInventoryController@getAllMonths');

	Route::get('get/office/code','OfficeController@getOfficeCode');

	Route::get('logout','SessionsController@destroy');

	Route::resource('maintenance/supply','SupplyController');

	Route::resource('maintenance/office','OfficeController');

	Route::resource('maintenance/item/type','ItemTypeController');

	Route::get('settings',['as'=>'settings.edit','uses'=>'SessionsController@edit']);

	Route::post('settings',['as'=>'settings.update','uses'=>'SessionsController@update']);

});

Route::group(['before'=>'auth|amo'],function(){

	Route::get('inventory/supply/stockcard/batch/form/accept',[
			'as' => 'supply.stockcard.batch.accept.form',
			'uses' => 'StockCardController@batchAcceptForm'
	]);

	Route::get('inventory/supply/stockcard/batch/form/release',[
			'as' => 'supply.stockcard.batch.release.form',
			'uses' => 'StockCardController@batchReleaseForm'
	]);

	Route::post('inventory/supply/stockcard/batch/accept',[
			'as' => 'supply.stockcard.batch.accept',
			'uses' => 'StockCardController@batchAccept'
	]);

	Route::post('inventory/supply/stockcard/batch/release',[
			'as' => 'supply.stockcard.batch.release',
			'uses' => 'StockCardController@batchRelease'
	]);

	Route::get('inventory/form/add',[
			'uses' => 'InventoryController@create'
	]);

	Route::get('inventory/supply/{id}/stockcard/release','StockCardController@releaseForm');

	Route::resource('inventory/supply.stockcard','StockCardController');


});

Route::group(['before'=>'auth|accounting'],function(){

	Route::get('inventory/supply/{id}/supplyledger/release','SupplyLedgerController@releaseForm');

	Route::resource('inventory/supply.supplyledger','SupplyLedgerController');

	Route::resource('accounting','AccountingController');


});

Route::group(['before'=>'session_start'],function(){
	//display login page
	Route::get('login', ['as'=>'login.index','uses'=>'SessionsController@create']);
	//send login request
	Route::post('login', ['as'=>'login.store','uses'=>'SessionsController@store']);

});