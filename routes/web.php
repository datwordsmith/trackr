<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\t\DashboardController;

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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('t')->middleware(['auth', 'isAdmin'])->group(function (){
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/projects', App\Http\Livewire\Admin\Project\Index::class);
    Route::get('/user_roles', App\Http\Livewire\Admin\UserRole\Index::class);
    /* Route::get('/users', App\Http\Livewire\Admin\User\Index::class);
    Route::get('/users/active', App\Http\Livewire\Admin\User\Active::class); */

    Route::prefix('users')->group(function () {
        Route::get('/', App\Http\Livewire\Admin\User\Index::class);
        Route::get('/active', App\Http\Livewire\Admin\User\Active::class);
        Route::get('/inactive', App\Http\Livewire\Admin\User\Inactive::class);
    });
});
