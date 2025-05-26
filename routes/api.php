<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('project', [ProjectController::class, 'index']);
Route::get('project/{project}', [ProjectController::class, 'show']);
Route::post('project', [ProjectController::class, 'store']);
Route::put('project/{project}', [ProjectController::class, 'update']);
Route::delete('project/{project}', [ProjectController::class, 'destroy']);

Route::get('task', [TaskController::class, 'index']);
Route::get('task/{task}', [TaskController::class, 'show']);
Route::post('task', [TaskController::class, 'store']);
Route::put('task/{task}', [TaskController::class, 'update']);
Route::delete('task/{task}', [TaskController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');