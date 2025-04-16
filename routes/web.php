<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\SecondHandPartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\CompatibilityController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\QuotationController;

// Public Routes
Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');

// Customer Routes
Route::middleware('auth')->group(function () {
    // Customer Profile
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::get('/customer/edit-profile', [CustomerController::class, 'editProfile'])->name('customer.edit_profile');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::delete('/customer/builds/{id}', [CustomerController::class, 'deleteBuild'])->name('customer.build.delete');

    // Build Routes
    Route::get('/build', [BuildController::class, 'index'])->name('build.index');
    Route::get('/build/purchase/{id}', [BuildController::class, 'purchase'])->name('build.purchase');
    Route::post('/build/save', [BuildController::class, 'saveBuild'])->name('build.save');
    Route::post('/build/update-progress', [BuildController::class, 'updateBuildProgress'])->name('build.update_progress');
    Route::get('/build/motherboards/{cpuId}', [BuildController::class, 'getCompatibleMotherboards']);
    Route::get('/build/gpus/{cpuId}/{motherboardId}', [BuildController::class, 'getCompatibleGpus']);
    Route::get('/build/rams/{motherboardId}', [BuildController::class, 'getCompatibleRams']);
    Route::get('/build/storages/{ramId}', [BuildController::class, 'getCompatibleStorages']);
    Route::get('/build/power-supplies/{storageId}', [BuildController::class, 'getCompatiblePowerSupplies']);
    Route::get('/build/{buildId}/generate-quotation', [BuildController::class, 'generateQuotation'])->name('build.generate_quotation');
    Route::get('/build/quotation/{buildId}', [BuildController::class, 'showQuotation'])->name('build.quotation');
    Route::get('/build/purchase/{build}', [BuildController::class, 'purchase'])->name('build.purchase');

    // Customer Dashboard Routes
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/build/{buildId}', [CustomerDashboardController::class, 'viewBuild'])->name('customer.build.view');

    // PC Building Compatibility Check
    Route::post('/build/check', [CompatibilityController::class, 'check'])->name('build.check');
});

    // Second-Hand Marketplace Routes
    Route::get('/secondhand', [SecondHandPartController::class, 'index'])->name('secondhand.index');
    Route::post('/secondhand', [SecondHandPartController::class, 'store'])->name('secondhand.store')->middleware(['auth', 'role:seller']);
    Route::get('/secondhand/{id}', [SecondHandPartController::class, 'show'])->name('secondhand.show');
    Route::post('/secondhand/{id}/buy', [SecondHandPartController::class, 'buy'])->name('secondhand.buy')->middleware(['auth', 'role:customer']);
    Route::get('/secondhand/{id}/buy', [SecondHandPartController::class, 'showBuyForm'])->name('secondhand.buy_form')->middleware(['auth', 'role:customer']);
    Route::get('/secondhand/confirmation/{payment_id}', [SecondHandPartController::class, 'confirmation'])->name('secondhand.confirmation')->middleware(['auth', 'role:customer']);

// Seller Routes
Route::middleware([\App\Http\Middleware\SellerAuth::class])->group(function () {
    Route::get('/sellers/parts/{id}/edit', [SellerController::class, 'editPart'])->name('seller.edit_part');
    Route::put('/sellers/parts/{id}', [SellerController::class, 'updatePart'])->name('seller.update_part');
    Route::delete('/sellers/parts/{id}', [SellerController::class, 'deletePart'])->name('seller.delete_part');
    Route::get('/sellers/dashboard', [SellerController::class, 'dashboard'])->name('sellers.dashboard');
    Route::get('/sellers/sell', [SellerController::class, 'showSellForm'])->name('seller.sell_form');
    Route::post('/sellers/sell', [SellerController::class, 'sell'])->name('seller.sell');
});

// Admin Routes
Route::middleware([\App\Http\Middleware\AdminAuth::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/approve/{id}', [AdminController::class, 'approve'])->name('admin.approve');
    Route::post('/admin/decline/{id}', [AdminController::class, 'decline'])->name('admin.decline');
    Route::post('/admin/delete-seller/{id}', [AdminController::class, 'deleteSeller'])->name('admin.delete_seller');
    Route::post('/admin/delete-customer/{id}', [AdminController::class, 'deleteCustomer'])->name('admin.delete_customer');
    Route::get('/admin/export-sales-report', [AdminController::class, 'exportSalesReport'])->name('admin.export_sales_report');
    Route::post('/admin/add-seller', [AdminController::class, 'addSeller'])->name('admin.add_seller');
    Route::post('/admin/edit-seller/{id}', [AdminController::class, 'editSeller'])->name('admin.edit_seller');
    Route::post('/admin/add-customer', [AdminController::class, 'addCustomer'])->name('admin.add_customer');
    Route::post('/admin/edit-customer/{id}', [AdminController::class, 'editCustomer'])->name('admin.edit_customer');
    Route::post('/admin/add-part', [AdminController::class, 'addPart'])->name('admin.add_part');
    Route::post('/admin/edit-part/{id}', [AdminController::class, 'editPart'])->name('admin.edit_part');
    Route::delete('/admin/delete-part/{id}', [AdminController::class, 'deletePart'])->name('admin.delete_part');
    Route::post('/admin/approve-part/{id}', [AdminController::class, 'approvePart'])->name('admin.approve_part');
    Route::post('/admin/decline-part/{id}', [AdminController::class, 'declinePart'])->name('admin.decline_part');
    Route::post('/admin/add-admin', [AdminController::class, 'addAdmin'])->name('admin.add_admin');
    Route::get('/admin/export-quotation-actions', [AdminController::class, 'exportQuotationActions'])->name('admin.export_quotation_actions');
    
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/export-quotation-actions', [AdminController::class, 'exportQuotationActions'])->name('admin.exportQuotationActions');
        Route::patch('/admin/quotation/{id}/status', [AdminController::class, 'updateQuotationStatus'])->name('admin.updateQuotationStatus');
        Route::delete('/admin/quotations/{id}', [AdminController::class, 'deleteQuotation'])->name('admin.deleteQuotation');
        Route::get('/admin/quotations/{id}', [AdminController::class, 'getQuotationDetails'])->name('admin.getQuotationDetails');
    
    
});

// Profile Route (Seller)
Route::get('/profile', function () {
    return view('sellers.profile');
})->name('profile')->middleware(['auth', 'role:seller']);

// Edit Profile Route (Customer)
Route::get('/editprofile', function () {
    return view('customer.edit-profile');
})->name('edit-profile')->middleware(['auth', 'role:customer']);

// Quotation Routes
Route::get('/quotation', [QuotationController::class, 'index'])->name('quotation.index');
Route::post('/quotation/generate', [QuotationController::class, 'generate'])->name('quotation.generate');
Route::get('/quotation/download/{spec}', [QuotationController::class, 'download'])->name('quotation.download');
Route::post('/quotation/send-email/{spec}', [QuotationController::class, 'sendBuildEmail'])->name('quotation.send-email');


Route::middleware('auth')->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::get('/customer/edit-profile', [CustomerController::class, 'editProfile'])->name('customer.edit_profile'); // Fixed route name
    Route::put('/customer/update-profile', [CustomerController::class, 'updateProfile'])->name('customer.update_profile');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::delete('/customer/builds/{id}', [CustomerController::class, 'deleteBuild'])->name('customer.build.delete');
});

