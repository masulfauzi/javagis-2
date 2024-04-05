<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PenggunaanLahan\Controllers\PenggunaanLahanController;

Route::controller(PenggunaanLahanController::class)->middleware(['web','auth'])->name('penggunaanlahan.')->group(function(){
	Route::get('/penggunaanlahan', 'index')->name('index');
	Route::get('/penggunaanlahan/data', 'data')->name('data.index');
	Route::get('/penggunaanlahan/create', 'create')->name('create');
	Route::post('/penggunaanlahan', 'store')->name('store');
	Route::get('/penggunaanlahan/{penggunaanlahan}', 'show')->name('show');
	Route::get('/penggunaanlahan/{penggunaanlahan}/edit', 'edit')->name('edit');
	Route::patch('/penggunaanlahan/{penggunaanlahan}', 'update')->name('update');
	Route::get('/penggunaanlahan/{penggunaanlahan}/delete', 'destroy')->name('destroy');
});