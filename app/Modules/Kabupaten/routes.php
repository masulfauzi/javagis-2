<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Kabupaten\Controllers\KabupatenController;

Route::controller(KabupatenController::class)->middleware(['web','auth'])->name('kabupaten.')->group(function(){
	Route::get('/kabupaten', 'index')->name('index');
	Route::get('/kabupaten/data', 'data')->name('data.index');
	Route::get('/kabupaten/create', 'create')->name('create');
	Route::post('/kabupaten', 'store')->name('store');
	Route::get('/kabupaten/{kabupaten}', 'show')->name('show');
	Route::get('/kabupaten/{kabupaten}/edit', 'edit')->name('edit');
	Route::patch('/kabupaten/{kabupaten}', 'update')->name('update');
	Route::get('/kabupaten/{kabupaten}/delete', 'destroy')->name('destroy');
});