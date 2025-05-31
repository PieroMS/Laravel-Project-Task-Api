<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/client', [ClientController::class, 'index'])->middleware('permission:index'); // Registrar este middleware en el bootstrap/app
    Route::post('/client', [ClientController::class, 'store'])->middleware('permission:create');
    Route::get('/client/{id}', [ClientController::class, 'show'])->middleware('permission:show');
    Route::put('/client/{id}', [ClientController::class, 'update'])->middleware('permission:update');
    Route::delete('/client/{id}', [ClientController::class, 'destroy'])->middleware('permission:destroy');
});