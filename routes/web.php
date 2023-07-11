<?php

use App\Http\Controllers\Frontend\DocumentController;
use App\Http\Controllers\ViewerController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get( '/viewer/{filename}', [ViewerController::class, 'view'])->name('viewer.index');
Route::get( '/document/{filename}', [DocumentController::class, 'view'])->name('document.detail');

