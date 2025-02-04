<?php

use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])
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

    // Student Routes
    Route::get('students', [StudentController::class, 'getStudents']);  // Get all students
    Route::get('students/{student_id}', [StudentController::class, 'getStudent']);  // Get student details
    Route::post('students', [StudentController::class, 'createStudent']);  // Create student
    Route::put('students/{student_id}', [StudentController::class, 'updateStudent']);  // Update student
    Route::delete('students/{student_id}', [StudentController::class, 'deleteStudent']);  // Delete student

// Course Routes for students
    Route::get('students/{student_id}/courses', [StudentController::class, 'getStudentCourses']);  // Get courses for a student
    Route::post('students/{student_id}/courses', [StudentController::class, 'enrollStudentInCourse']);  // Enroll student in a course
    Route::delete('students/{student_id}/courses/{course_id}', [StudentController::class, 'removeCourseFromStudent']);  // Remove a course from a student

// Payment Routes for students
    Route::get('students/{student_id}/payments', [StudentController::class, 'getStudentPayments']);  // Get payments for a student
    Route::post('students/{student_id}/payments', [StudentController::class, 'addPaymentForStudent']);  // Add a payment for a student
    Route::delete('students/{student_id}/payments/{payment_id}', [StudentController::class, 'deletePaymentForStudent']);  // Delete a payment for a student
});
