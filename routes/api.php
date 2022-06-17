<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretController;

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



Route::get('v1/secret/{hash}', [SecretController::class, 'getSecret'])->name('getSecret');
Route::post('v1/secret/', [SecretController::class, 'postSecret'])->name('postSecret');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
