<?php

use App\Http\Controllers\TechnicalController;
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
use App\Http\Controllers\TechnicianController;
use Illuminate\Support\Facades\Auth;


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
    // Customer Profile and Dashboard
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/build/{buildId}', [CustomerDashboardController::class, 'viewBuild'])->name('customer.build.view');
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::get('/customer/edit-profile', [CustomerController::class, 'editProfile'])->name('customer.edit_profile');
    Route::put('/customer/update-profile', [CustomerController::class, 'updateProfile'])->name('customer.update_profile');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::delete('/customer/builds/{id}', [CustomerController::class, 'deleteBuild'])->name('customer.build.delete');
    Route::post('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');

    // Build Routes
    Route::get('/build1', [BuildController::class, 'index'])->name('build.index');
    Route::get('/build/purchase/{build}', [BuildController::class, 'purchase'])->name('build.purchase');
    Route::post('/build/save', [BuildController::class, 'saveBuild'])->name('build.save');
    Route::post('/build/update-progress', [BuildController::class, 'updateBuildProgress'])->name('build.update_progress');
    Route::get('/build/motherboards/{cpuId}', [BuildController::class, 'getCompatibleMotherboards']);
    Route::get('/build/gpus/{cpuId}/{motherboardId}', [BuildController::class, 'getCompatibleGpus']);
    Route::get('/build/rams/{motherboardId}', [BuildController::class, 'getCompatibleRams']);
    Route::get('/build/storages/{ramId}', [BuildController::class, 'getCompatibleStorages']);
    Route::get('/build/power-supplies/{storageId}', [BuildController::class, 'getCompatiblePowerSupplies']);
    Route::get('/build/{buildId}/generate-quotation', [BuildController::class, 'generateQuotation'])->name('build.generate_quotation');
    Route::get('/build/quotation/{buildId}', [BuildController::class, 'showQuotation'])->name('build.quotation');

    // PC Building Compatibility Check
    Route::post('/build/check', [CompatibilityController::class, 'check'])->name('build.check');

    // Profile and Edit Profile Routes
    Route::get('/profile', function () {
        if (Auth::check() && Auth::user()->role === 'seller') {
            return view('sellers.profile');
        }
        return redirect()->route('login')->with('error', 'You must be a seller to access this page.');
    })->name('profile');

    Route::get('/editprofile', function () {
        if (Auth::check() && Auth::user()->role === 'customer') {
            return view('customer.edit-profile');
        }
        return redirect()->route('login')->with('error', 'You must be a customer to access this page.');
    })->name('edit-profile');
});

// Second-Hand Marketplace Routes
Route::get('/secondhand', [SecondHandPartController::class, 'index'])->name('secondhand.index');
Route::get('/secondhand/create', [SecondHandPartController::class, 'create'])->name('secondhand.create');
Route::post('/secondhand', [SecondHandPartController::class, 'store'])->name('secondhand.store');
Route::get('/secondhand/{id}', [SecondHandPartController::class, 'show'])->name('secondhand.show');
Route::get('/secondhand/{id}/buy', [SecondHandPartController::class, 'showBuyForm'])->name('secondhand.buy.form');
Route::post('/secondhand/{id}/buy', [SecondHandPartController::class, 'buy'])->name('secondhand.buy');
Route::post('/secondhand/{id}/buy/paypal', [SecondHandPartController::class, 'buyPayPal'])->name('secondhand.buy.paypal');
Route::get('/secondhand/{id}/buy/success', [SecondHandPartController::class, 'buySuccess'])->name('secondhand.buy.success');
Route::get('/secondhand/sell', [SecondHandPartController::class, 'showSellForm'])->name('secondhand.sell.form');
Route::post('/secondhand/sell', [SecondHandPartController::class, 'sell'])->name('secondhand.sell');

// Seller Routes
Route::middleware('auth')->group(function () {
    Route::get('/sellers/dashboard', [SellerController::class, 'dashboard'])->name('sellers.dashboard');
    Route::get('/sellers/sell', [SellerController::class, 'showSellForm'])->name('seller.sell_form');
    Route::post('/sellers/sell', [SellerController::class, 'sell'])->name('seller.sell');
    Route::get('/sellers/parts/{id}/edit', [SellerController::class, 'editPart'])->name('seller.edit_part');
    Route::put('/sellers/parts/{id}', [SellerController::class, 'updatePart'])->name('seller.update_part');
    Route::delete('/sellers/parts/{id}', [SellerController::class, 'deletePart'])->name('seller.delete_part');
    Route::post('/sellers/orders/{id}/update-status', [SellerController::class, 'updateOrderStatus'])->name('seller.orders.update-status');

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
    Route::patch('/admin/quotation/{id}/status', [AdminController::class, 'updateQuotationStatus'])->name('admin.update_quotation_status');
    Route::delete('/admin/quotations/{id}', [AdminController::class, 'deleteQuotation'])->name('admin.delete_quotation');
    Route::get('/admin/quotations/{id}', [AdminController::class, 'getQuotationDetails'])->name('admin.get_quotation_details');
    Route::patch('/admin/orders/{id}/verify', [AdminController::class, 'updateVerificationStatus'])->name('admin.orders.verify');


    Route::post('/admin/cpus', [AdminController::class, 'addCpu'])->name('cpus.store');
    Route::patch('/admin/cpus/{id}', [AdminController::class, 'editCpu'])->name('cpus.update');
    Route::delete('/admin/cpus/{id}', [AdminController::class, 'deleteCpu'])->name('cpus.destroy');

    Route::post('/admin/motherboards', [AdminController::class, 'addMotherboard'])->name('motherboards.store');
    Route::patch('/admin/motherboards/{id}', [AdminController::class, 'editMotherboard'])->name('motherboards.update');
    Route::delete('/admin/motherboards/{id}', [AdminController::class, 'deleteMotherboard'])->name('motherboards.destroy');

    Route::post('/admin/gpus', [AdminController::class, 'addGpu'])->name('gpus.store');
    Route::patch('/admin/gpus/{id}', [AdminController::class, 'editGpu'])->name('gpus.update');
    Route::delete('/admin/gpus/{id}', [AdminController::class, 'deleteGpu'])->name('gpus.destroy');

    Route::post('/admin/rams', [AdminController::class, 'addRam'])->name('rams.store');
    Route::patch('/admin/rams/{id}', [AdminController::class, 'editRam'])->name('rams.update');
    Route::delete('/admin/rams/{id}', [AdminController::class, 'deleteRam'])->name('rams.destroy');

    Route::post('/admin/storages', [AdminController::class, 'addStorage'])->name('storages.store');
    Route::patch('/admin/storages/{id}', [AdminController::class, 'editStorage'])->name('storages.update');
    Route::delete('/admin/storages/{id}', [AdminController::class, 'deleteStorage'])->name('storages.destroy');

    Route::post('/admin/power-supplies', [AdminController::class, 'addPowerSupply'])->name('power_supplies.store');
    Route::patch('/admin/power-supplies/{id}', [AdminController::class, 'editPowerSupply'])->name('power_supplies.update');
    Route::delete('/admin/power-supplies/{id}', [AdminController::class, 'deletePowerSupply'])->name('power_supplies.destroy');

    Route::post('/admin/technicians', [AdminController::class, 'storeTechnician'])->name('admin.technicians.store');
    Route::patch('/admin/technicians/{id}', [AdminController::class, 'updateTechnician'])->name('admin.technicians.update');
    Route::delete('/admin/technicians/{id}', [AdminController::class, 'destroyTechnician'])->name('admin.technicians.destroy');

});

// Quotation Routes
Route::get('/quotation', [QuotationController::class, 'index'])->name('quotation.index');
Route::post('/quotation/generate', [QuotationController::class, 'generate'])->name('quotation.generate');
Route::get('/quotation/download/{spec}', [QuotationController::class, 'download'])->name('quotation.download');
Route::post('/quotation/send-email/{spec}', [QuotationController::class, 'sendBuildEmail'])->name('quotation.send_email');


//Route::patch('/customer/orders/{id}/mark-received', [CustomerController::class, 'orders'])->name('customer.orders');


Route::get('/technical', [TechnicalController::class, 'index'])->name('technical.network');