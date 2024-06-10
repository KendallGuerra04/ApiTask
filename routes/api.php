<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Obtención de datos
Route::get('/tasks', [TaskController::class, 'ReadsTask']);
Route::get('/task/{id}', [TaskController::class, 'ReadTask']);

// Cargar tareas
Route::post('/task',[TaskController::class, 'CreateTask']);

// Actualizar tareas
Route::put('/task/{id}', [TaskController::class, 'UpdateTask']);

// Eliminar tareas
Route::delete('/task/{id}', [TaskController::class, 'DeleteTask']);
