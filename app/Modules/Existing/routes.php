<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Existing\Controllers\ExistingController;

Route::controller(ExistingController::class)->middleware(['web','auth'])->name('existing.')->group(function(){
	Route::get('/existing', 'index')->name('index');
	Route::get('/existing/data', 'data')->name('data.index');
	Route::get('/existing/create', 'create')->name('create');
	Route::post('/existing', 'store')->name('store');
	Route::get('/existing/{existing}', 'show')->name('show');
	Route::get('/existing/{existing}/edit', 'edit')->name('edit');
	Route::patch('/existing/{existing}', 'update')->name('update');
	Route::get('/existing/{existing}/delete', 'destroy')->name('destroy');
});