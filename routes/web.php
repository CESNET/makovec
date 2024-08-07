<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FakeController;
use App\Http\Controllers\ShibbolethController;
use App\Http\Controllers\UserCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\UserSubroleController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::view('up', 'health-up');
Route::view('/', 'welcome')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::view('home', 'home')->name('home');

    Route::resource('devices', DeviceController::class);
    Route::resource('categories', CategoryController::class);

    Route::resource('users', UserController::class)->only('index', 'show', 'update');

    Route::patch('users/{user}/categories', UserCategoryController::class)->name('users.categories');
    Route::patch('users/{user}/role', UserRoleController::class)->name('users.role');
    Route::patch('users/{user}/subrole', UserSubroleController::class)->name('users.subrole');
    Route::patch('users/{user}/status', UserStatusController::class)->name('users.status');
});

Route::get('login', [ShibbolethController::class, 'create'])->name('login')->middleware('guest');
Route::get('auth', [ShibbolethController::class, 'store'])->middleware('guest');
Route::get('logout', [ShibbolethController::class, 'destroy'])->name('logout')->middleware('auth');

if (App::environment('local', 'testing')) {
    Route::post('fakelogin', [FakeController::class, 'store'])->name('fakelogin');
    Route::get('fakelogout', [FakeController::class, 'destroy'])->name('fakelogout');
}
