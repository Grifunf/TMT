<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/lobby', [GameController::class, 'ListGames']);
Route::put('/lobby', [GameController::class, 'CreateGame']);
Route::get('/game', [GameController::class, 'GameInfo']);
Route::post('/game', [GameController::class, 'JoinGame']);
Route::put('/game', [GameController::class, 'MakeAction']);

Route::put('/player', [PlayerController::class, 'CreatePlayer']);