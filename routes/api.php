<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ChecklistController;

Route::post('/login', [UserController::class, 'login']);
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); 
    Route::get('/{id}', [UserController::class, 'show']); 
    Route::post('/create', [UserController::class, 'store']);   
    Route::post('/update/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checklists', [ChecklistController::class, 'index']);
    Route::post('/checklists/create', [ChecklistController::class, 'store']);
    Route::put('/checklists/update/{id}', [ChecklistController::class, 'update']);
    Route::delete('/checklists/delete/{id}', [ChecklistController::class, 'destroy']);
    Route::put('/checklists/{id}/done', [ChecklistController::class, 'markAsDone']);

    // Admin only route (tambahkan middleware jika perlu)
    Route::get('/admin/checklists', [ChecklistController::class, 'allChecklists']);
// });
