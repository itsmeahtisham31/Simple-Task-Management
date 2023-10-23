<?php

use App\Http\Controllers\AuthController;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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


Route::get('/',[AuthController::class,'index'])->name('login');
Route::get('register',[AuthController::class,'register_view'])->name('register');
Route::post('register_post',[AuthController::class,'register_post'])->name('register.post');
Route::post('login',[AuthController::class,'login_post'])->name('login.post');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');

Route::middleware(['prevent_back', 'auth.check'])->group(function () {
    Route::get('tasks',[TaskController::class,'index'])->name('tasks.index');
    Route::get('tasks/{id}/edit',[TaskController::class,'edit'])->name('tasks.edit');
    Route::post('tasks/{id}/',[TaskController::class,'update'])->name('tasks.update');
    Route::post('tasks',[TaskController::class,'store'])->name('tasks.store');
    Route::delete('tasks/{id}/',[TaskController::class,'destroy'])->name('tasks.destroy');
    Route::get('get-data', [TaskController::class,'getData'])->name('get.data');
});
