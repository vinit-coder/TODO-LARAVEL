<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
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

// Display tasks
Route::get('/', [TodoController::class, 'index'])->name('task.index');

// Display all tasks
Route::get('all_tasks/{task}', [TodoController::class, 'showAll'])->name('task.show');

// Create a new task
Route::post('/tasks', [TodoController::class, 'create'])->name('task.create');

// Update an existing task
Route::put('update_task/{id}', [TodoController::class, 'update'])->name('task.update');

// Delete a task
Route::delete('delete_task/{id}', [TodoController::class, 'destroy'])->name('task.destroy');
