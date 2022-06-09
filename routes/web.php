<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMembersController;
use App\Http\Controllers\TaskController;
use App\Models\ProjectMembers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class)->name('index', 'projects.index');

    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);

    Route::post('/projects/{project}/members', [ProjectMembersController::class, 'store']);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
