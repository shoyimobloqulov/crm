<?php

use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])
    ->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    // Rollar bilan ishlash
    Route::post('/roles', [RolePermissionController::class, 'createRole']); // Yangi rol yaratish
    Route::get('/roles', [RolePermissionController::class, 'getRoles']); // Barcha rollarni olish

    // Permissionlar bilan ishlash
    Route::post('/permissions', [RolePermissionController::class, 'createPermission']); // Yangi permission yaratish
    Route::get('/permissions', [RolePermissionController::class, 'getPermissions']); // Barcha permissionlarni olish

    // Ruxsatlarni rollarga biriktirish
    Route::post('/roles/assign-permission', [RolePermissionController::class, 'assignPermissionToRole']);

    // Foydalanuvchiga rol biriktirish
    Route::post('/users/assign-role', [RolePermissionController::class, 'assignRoleToUser']);
    Route::get('/users/{user_id}/roles', [RolePermissionController::class, 'getUserRoles']); // Foydalanuvchining rollarini olish

    // Foydalanuvchiga permission biriktirish
    Route::post('/users/assign-permission', [RolePermissionController::class, 'assignPermissionToUser']);
    Route::get('/users/{user_id}/permissions', [RolePermissionController::class, 'getUserPermissions']); // Foydalanuvchining ruxsatlarini olish
});
