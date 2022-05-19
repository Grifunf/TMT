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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function(){
    $service = new GameService;
    $service->CreateGame('test', 5);
    return view('test');
});