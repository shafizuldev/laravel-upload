<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BatchUploadController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', function () {
    return view('index');
});

Route::group(['prefix' => 'batch-upload', 'as' => 'batch_upload.'], function () {
    Route::get('/', [BatchUploadController::class, 'index'])->name('index');
    Route::post('/', [BatchUploadController::class, 'store'])->name('store');
});
