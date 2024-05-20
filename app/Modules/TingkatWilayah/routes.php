<?php

use Illuminate\Support\Facades\Route;
use App\Modules\TingkatWilayah\Controllers\TingkatWilayahController;

Route::controller(TingkatWilayahController::class)->middleware(['web','auth'])->name('tingkatwilayah.')->group(function(){
	Route::get('/tingkatwilayah', 'index')->name('index');
	Route::get('/tingkatwilayah/data', 'data')->name('data.index');
	Route::get('/tingkatwilayah/create', 'create')->name('create');
	Route::post('/tingkatwilayah', 'store')->name('store');
	Route::get('/tingkatwilayah/{tingkatwilayah}', 'show')->name('show');
	Route::get('/tingkatwilayah/{tingkatwilayah}/edit', 'edit')->name('edit');
	Route::patch('/tingkatwilayah/{tingkatwilayah}', 'update')->name('update');
	Route::get('/tingkatwilayah/{tingkatwilayah}/delete', 'destroy')->name('destroy');
});