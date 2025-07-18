<?php

use Illuminate\Support\Facades\Route;
// Các controller của dự án hiện tại
use App\Http\Controllers\KingExpressBus\Admin\AdminController;
use App\Http\Controllers\KingExpressBus\Admin\CategoryController;
use App\Http\Controllers\KingExpressBus\Admin\NewsController;
use App\Http\Controllers\KingExpressBus\Admin\TrainingController;
use App\Http\Controllers\KingExpressBus\Admin\CustomerController;
use App\Http\Controllers\KingExpressBus\Admin\TeacherController;

// Controller và Middleware cho Auth
use App\Http\Controllers\KingExpressBus\Auth\AuthenticationController;
use App\Http\Middleware\AuthenticationMiddleware;

// Auth routes
Route::get('/admin/login', [AuthenticationController::class, "login"])->name("admin.login");
Route::get('/admin/logout', [AuthenticationController::class, "logout"])->name("admin.logout");
Route::post('/admin/authenticate', [AuthenticationController::class, "authenticate"])->name("admin.authenticate");

// Admin routes group
Route::prefix('admin')->name("admin.")->middleware(AuthenticationMiddleware::class)->group(function () {
    // Dashboard - bây giờ là quản lý trang chủ
    Route::get("/dashboard", [AdminController::class, "index"])->name("dashboard.index");
    // THÊM ROUTE UPDATE CHO DASHBOARD
    Route::post("/dashboard/update", [AdminController::class, "update"])->name("dashboard.update");

    // Các resource của dự án
    Route::resource("categories", CategoryController::class)->except(['show']);
    Route::resource("news", NewsController::class)->except(['show']);
    Route::resource("training", TrainingController::class)->except(['show']);
    Route::resource("customers", CustomerController::class)->only(['index', 'show', 'destroy']);
    Route::resource("teachers", TeacherController::class)->except(['show']);
});

// CKFinder Plugin routes
Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');

Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');