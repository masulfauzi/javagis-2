<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PenggunaanLahan\Controllers\PenggunaanLahanController;

Route::controller(PenggunaanLahanController::class)->middleware(['web','auth'])->name('penggunaanlahan.')->group(function(){
	//route custom
	Route::get('/survey', 'survey')->name('map.index');
	Route::get('/survey/create/{jenis}', 'create_survey')->name('map.create');
	Route::post('/survey/store/', 'store_survey')->name('map.store');

	
	
	// route bawaan
	Route::get('/penggunaanlahan', 'index')->name('index');
	Route::get('/penggunaanlahan/data', 'data')->name('data.index');
	Route::get('/penggunaanlahan/create', 'create')->name('create');
	Route::post('/penggunaanlahan', 'store')->name('store');
	Route::get('/penggunaanlahan/{penggunaanlahan}', 'show')->name('show');
	Route::get('/penggunaanlahan/{penggunaanlahan}/edit', 'edit')->name('edit');
	Route::patch('/penggunaanlahan/{penggunaanlahan}', 'update')->name('update');
	Route::get('/penggunaanlahan/{penggunaanlahan}/delete', 'destroy')->name('destroy');
});