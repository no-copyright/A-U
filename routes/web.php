<?php

use Illuminate\Support\Facades\Route;

// --- Controller Imports ---
use App\Http\Controllers\KingExpressBus\Admin\AdminController;
use App\Http\Controllers\KingExpressBus\Admin\CategoryController;
use App\Http\Controllers\KingExpressBus\Admin\ContactController;
use App\Http\Controllers\KingExpressBus\Admin\CustomerController;
use App\Http\Controllers\KingExpressBus\Admin\DocumentController;
use App\Http\Controllers\KingExpressBus\Admin\NewsController;
use App\Http\Controllers\KingExpressBus\Admin\ParentsCornerController;
use App\Http\Controllers\KingExpressBus\Admin\TeacherController;
use App\Http\Controllers\KingExpressBus\Admin\TrainingController;
use App\Http\Controllers\KingExpressBus\Auth\AuthenticationController;
use App\Http\Middleware\AuthenticationMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL to the admin login page
Route::get('/', function () {
    return to_route('admin.login');
});

// --- Authentication Routes ---
Route::get('/admin/login', [AuthenticationController::class, "login"])->name("admin.login");
Route::get('/admin/logout', [AuthenticationController::class, "logout"])->name("admin.logout");
Route::post('/admin/authenticate', [AuthenticationController::class, "authenticate"])->name("admin.authenticate");


// --- Admin Panel Routes (Protected by Middleware) ---
Route::prefix('admin')->name("admin.")->middleware(AuthenticationMiddleware::class)->group(function () {
    // Dashboard & Homepage Management
    Route::get("/dashboard", [AdminController::class, "index"])->name("dashboard.index");
    Route::post("/dashboard/update", [AdminController::class, "update"])->name("dashboard.update");

    // Contact Page Management
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact/update', [ContactController::class, 'update'])->name('contact.update');

    // CRUD Resources
    Route::resource("categories", CategoryController::class)->except(['show']);
    Route::resource("news", NewsController::class)->except(['show']);
    Route::resource("training", TrainingController::class)->except(['show']);
    Route::resource("teachers", TeacherController::class)->except(['show']);
    Route::resource("parents-corner", ParentsCornerController::class)->except(['show']);
    Route::resource("documents", DocumentController::class)->except(['show']);

    // Customer Routes (with custom status update)
    Route::resource("customers", CustomerController::class)->only(['index', 'show', 'destroy']);
    Route::post("customers/{customer}/update-status", [CustomerController::class, 'updateStatus'])->name('customers.updateStatus');
});


// --- Third-Party Routes ---
Route::group(['prefix' => 'ckfinder', 'middleware' => ['web']], function () {
    Route::any('/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')->name('ckfinder_connector');
    Route::any('/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')->name('ckfinder_browser');
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    // Homepage
    Route::get('/homepage', [AdminController::class, 'getHomePageApi']);

    // Categories & News
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::get('/categories/{categorySlug}/news', [CategoryController::class, 'getNewsByCategorySlug']);
    Route::get('/knowledge-news', [NewsController::class, 'getKnowledgeNewsApi']);
    Route::get('/news', [NewsController::class, 'getNewsList']);
    Route::get('/news/{slug}', [NewsController::class, 'getNewsDetailBySlug']);

    // Customers
    Route::post('/customers', [CustomerController::class, 'store']);

    // Trainings
    Route::get('/training', [TrainingController::class, 'getTrainingListApi']);
    Route::get('/training/{slug}', [TrainingController::class, 'getTrainingDetailApi']);

    // Teachers
    Route::get('/teachers', [TeacherController::class, 'getTeacherListApi']);
    Route::get('/teachers/{slug}', [TeacherController::class, 'getTeacherDetailApi']);

    // Contact
    Route::get('/contact', [ContactController::class, 'getContactApi']);

    // Parents' Corner
    Route::get('/parents-corner', [ParentsCornerController::class, 'getParentsCornerApi']);
    Route::get('/parents-corner/{slug}', [ParentsCornerController::class, 'getParentsCornerDetailApi']);

    // Documents
    Route::get('/documents', [DocumentController::class, 'getDocumentsApi']);
});
