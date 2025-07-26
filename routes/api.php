<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ChecklistController;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/checklists', [ChecklistController::class, 'index']);
Route::post('/checklists/create', [ChecklistController::class, 'store']);
Route::put('/checklists/update/{id}', [ChecklistController::class, 'update']);
Route::delete('/checklists/delete/{id}', [ChecklistController::class, 'destroy']);
Route::post('/checklists/done/{id}', [ChecklistController::class, 'markAsDone']);

Route::get('/all-checklists', [ChecklistController::class, 'allChecklists']);
