<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;


Route::middleware('api')->group(function () {
    Route::post('/upload', 'App\Http\Controllers\ConversionController@upload');
    Route::get('/download/{filename}', 'App\Http\Controllers\ConversionController@download');
    Route::post('/upload', [FileController::class, 'upload']);

});
