<?php

use App\Http\Controllers\api\GuidelineController;
use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

 

Route::post('v1/login', [AuthController::class, 'login']); 

Route::group([
    'prefix' => 'v1',
    'as' => 'v1.',
    'middleware' => ['jwt.verify']
], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [UserController::class, 'userProfile']);
    Route::post('/user-update', [UserController::class, 'userUpdate']);
    Route::post('/update-password', [UserController::class, 'updatePassword']);
    Route::get('/get-guidline-lists', [GuidelineController::class, 'getGuidlineLists']);
});
