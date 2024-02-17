<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/', [AuthController::class, 'redirect'])->name('redirect');
    Route::get('/', [AuthController::class, 'callback'])->name('callback');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::apiResource('tasks', TaskController::class)->except(['index', 'show', 'store']);
Route::post('tasks/{project}', [TaskController::class, 'store'])->name('tasks.store');
Route::post('tasks/{task}/order', [TaskController::class, 'move'])->name('tasks.move');

Route::post('status/{id}', StatusController::class)->name('status.update');

Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
Route::post('/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/{project}/order', [ProjectController::class, 'move'])->name('projects.move');
