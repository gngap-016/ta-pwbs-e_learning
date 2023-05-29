<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Dashboard\Admin\DashboardController;
use App\Http\Controllers\Dashboard\Admin\PostController;
use App\Http\Controllers\Dashboard\Admin\StudentController;
use Illuminate\Support\Facades\Http;
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

Route::get('/admin/posts', [PostController::class, 'index'])->middleware('is_admin');

Route::get('/', [AuthenticationController::class, 'login']);
Route::post('/login', [AuthenticationController::class, 'processLogin']);
Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware('is_login');

Route::middleware('is_login')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index']);

    Route::get('/admin/materies', [PostController::class, 'index']);

    Route::get('/admin/users/students', [StudentController::class, 'index']);
});

Route::get('/check', function() {
    if(isset($_COOKIE['my_token']) && isset($_COOKIE['my_key'])) {
        $user = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $_COOKIE['my_token'],
        ])->get(env('SERVER_API') . 'users/' . $_COOKIE['my_key']);
    
        return json_decode($user);
    }

    abort(403);
});