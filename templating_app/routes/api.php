<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UsersController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::group([

        'middleware' => 'api',
        'prefix' => 'auth'

    ], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::get('/me', [AuthController::class, 'me']);
    });
    Route::get('/customers', [UsersController::class, 'getUsers']);
    Route::post('/customers', [UsersController::class, 'createUser']);
    Route::patch('/customers/{id}', [UsersController::class, 'updateUser']);
    Route::delete('/customers/{id}', [UsersController::class, 'deleteUser']);
    Route::get('/templates', [TemplateController::class, 'getTemplates']);
    Route::post('/templates', [TemplateController::class, 'createTemplate']);
    Route::patch('/templates/{id}', [TemplateController::class, 'updateTemplate']);
    Route::get('/templates/{id}', [TemplateController::class, 'getTemplate']);
    Route::delete('/templates/{id}', [TemplateController::class, 'deleteTemplate']);
    Route::patch('/templates/questions/{id}', [TemplateController::class, 'updateQuestion']);
//});
