<?php

use App\Http\Controllers\TVShowController;
use App\Mail\TVShowsUpdatesNotif;
use App\Models\User;
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

Route::get('/testMe', function () {
    abort_if(auth()->guest(), 404);

    $user = User::whereId(20)->first();

    $message = (new TVShowsUpdatesNotif($user))->render();

    return $message;
//    sendMail();
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
