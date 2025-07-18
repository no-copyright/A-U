<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KingExpressBus\Admin\AdminController;
use App\Http\Controllers\KingExpressBus\Admin\ProvinceController;
use App\Http\Controllers\KingExpressBus\Admin\DistrictController;
use App\Http\Controllers\KingExpressBus\Admin\RouteController;
use App\Http\Controllers\KingExpressBus\Admin\BusController;
use App\Http\Controllers\KingExpressBus\Admin\BusRouteController;
use App\Http\Controllers\KingExpressBus\Admin\BookingController;
use App\Http\Controllers\KingExpressBus\Admin\MenuController;

// [cite: 3141]
use App\Http\Controllers\KingExpressBus\Auth\AuthenticationController;
use App\Http\Middleware\AuthenticationMiddleware;
;

// Auth
Route::get('/admin/login', [AuthenticationController::class, "login"])->name("admin.login"); // [cite: 3146]
Route::get('/admin/logout', [AuthenticationController::class, "logout"])->name("admin.logout"); // [cite: 3146]
Route::post('/admin/authenticate', [AuthenticationController::class, "authenticate"])->name("admin.authenticate"); // [cite: 3146]

Route::prefix('admin')->name("admin.")->middleware(AuthenticationMiddleware::class)->group(function () { // [cite: 3147]
    Route::get("/dashboard", [AdminController::class, "index"])->name("dashboard.index");
    Route::post("/dashboard", [AdminController::class, "update"])->name("dashboard.update");

    Route::resource("provinces", ProvinceController::class);
    Route::resource("districts", DistrictController::class);
    Route::resource("routes", RouteController::class);
    Route::resource("buses", BusController::class);
    Route::resource("bus_routes", BusRouteController::class);
    Route::resource("bookings", BookingController::class);
    Route::resource("menus", MenuController::class);
    Route::post('/menus/update-order', [MenuController::class, 'updateOrder'])->name('menus.updateOrder');
});

// CK Plugin
Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction') // [cite: 3148]
->name('ckfinder_connector'); // [cite: 3148]

Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction') // [cite: 3148]
->name('ckfinder_browser'); // [cite: 3148]
