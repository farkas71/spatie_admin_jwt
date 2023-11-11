<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;


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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('/')->group(function () {
        Route::get('profile', [ProfileController::class, 'edit'])->middleware('permission:admin_profil')->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->middleware('permission:admin_profil')->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->middleware('permission:admin_profil')->name('profile.destroy');
    });


    Route::prefix('/users')->middleware('permission:admin_menu')->group(function () {
        Route::get('/', [UserController::class, 'list'])->middleware('permission:read users')->name('users.list');
        Route::get('/uj', [UserController::class, 'create'])->middleware('permission:create users')->name('users.create');
        Route::post('/uj', [UserController::class, 'createProces'])->middleware('permission:create users')->name('users.add');
        Route::get('/szerkeszt/{userName}', [UserController::class, 'edit'])->middleware('permission:update users')->name('users.edit');
        Route::post('/szerkeszt/{userName}', [UserController::class, 'editProces'])->middleware('permission:update users')->name('users.editProces');
        Route::get('/torol/{userName}', [UserController::class, 'delete'])->middleware('permission:delete users')->name('users.delete');
    });

    Route::prefix('/roles')->middleware('permission:admin_menu')->group(function () {
        Route::get('/', [RoleController::class, 'list'])->middleware('permission:read roles')->name('roles.list');
        Route::get('/uj', [RoleController::class, 'create'])->middleware('permission:create roles')->name('roles.create');
        Route::post('/uj', [RoleController::class, 'createProces'])->middleware('permission:create roles')->name('roles.add');
        Route::get('/szerkeszt/{roleName}', [RoleController::class, 'edit'])->middleware('permission:update roles')->name('roles.edit');
        Route::post('/szerkeszt/{roleName}', [RoleController::class, 'editProces'])->middleware('permission:update roles')->name('roles.editProces');
        Route::get('/torol/{roleName}', [RoleController::class, 'delete'])->middleware('permission:delete roles')->name('roles.delete');
    });

    // Route::prefix('/permissions')->middleware('permission:admin_menu')->group(function () {
    // });


});

require __DIR__ . '/auth.php';
