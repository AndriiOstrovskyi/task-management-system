<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/tasks', TasksController::class);

    Route::group(['prefix' => 'teams'], function () {
        Route::get('/', [TeamController::class, 'index']);
        Route::post('/', [TeamController::class, 'store']);
        Route::post('/{teamId}/users ', [TeamController::class, 'addUserToTeam']);
        Route::delete('/{teamId}/users/{userId}', [TeamController::class, 'removeUserFromTeam']);
    });

    Route::prefix('tasks/{taskId}')->group(function () {
        Route::post('comments', [CommentController::class, 'store']);
        Route::get('comments', [CommentController::class, 'index']);
    });
    
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);
});
