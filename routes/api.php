<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RemedyController;
use App\Http\Controllers\ProjectController;

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

Route::post('registration', [UserController::class, 'postRegistrationAPI'])->name('post.register');
Route::get('remedy', [RemedyController::class, 'getRemedies']);
Route::get('slide-chart/{state}/{project_type_id}', [ProjectController::class, 'getSlideChart']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
