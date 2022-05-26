<?php

use Illuminate\Support\Facades\Route;
use App\Services\GameService;
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

Route::middleware('verify.registration')->group(function () {
    Route::get('/', function () { return view('home'); });
    Route::get('/game', function() { return view('game'); });
});

Route::get('/register', function() { return view('register'); });