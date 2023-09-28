<?php

use App\Http\Controllers\TVShowController;
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
    return view('welcome');
});

Route::get('/show/{tvshow}', [TVShowController::class, "fullInfo"])->name('display-show-full-info');
Route::get('/timeline', [TVShowController::class, "timeline"])->name('display-timeline')->middleware(['auth']);
Route::get('/search', [TVShowController::class, "search"])->name('search-full-results');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
