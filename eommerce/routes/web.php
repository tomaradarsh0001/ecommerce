<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Welcome Admin!';
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::get('/users/assign-role', [UserRoleController::class, 'index'])->name('users.assign-role');
    Route::post('/users/assign-role', [UserRoleController::class, 'assign'])->name('users.assign-role.store');
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::get('/users/{user}/edit-roles', [UserController::class, 'editRoles'])
        ->name('users.edit-roles');
    Route::put('/users/{user}/update-roles', [UserController::class, 'updateRoles'])
        ->name('users.update-roles');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
    Route::post('/permissions/check-exists', [PermissionController::class, 'checkExists'])
    ->name('permissions.check-exists');
    Route::patch('permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])
    ->name('permissions.toggle-status');
    Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])
    ->name('roles.toggle-status');
    Route::post('/roles/check-exists', [RoleController::class, 'checkExists'])
    ->name('roles.check-exists');});
    Route::get('roles/{role}/permissions', [RoleController::class, 'editPermissions'])
    ->name('roles.edit-permissions');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
        ->name('roles.update-permissions');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard' , function () {
    return view('dashboard');    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
