<?php

use App\Http\Controllers\ImportController;
use App\Models\Import;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome', [
        'imports' => Import::latest()->get(),
    ]);
})->name('welcome');

Route::post('/import', ImportController::class)->name('import');
