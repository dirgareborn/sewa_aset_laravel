<?php

use App\Http\Controllers\Admin\AccountBankController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\ApiKeyController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\BookingController;     
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SystemInfoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::match(['get', 'post'], 'admin/login', [AdminController::class, 'login'])
    ->middleware('guest:admin')
    ->name('admin.login');

// Admin routes with middleware
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Admin profile
    Route::match(['get', 'post'], 'update-password', [AdminController::class, 'updatePassword'])->name('update-password');
    Route::match(['get', 'post'], 'profil', [AdminController::class, 'updateDetail'])->name('profil');
    Route::post('check-current-password', [AdminController::class, 'checkCurrentPassword'])->name('check-current-password');
    Route::get('logout', [AdminController::class, 'logout'])->name('logout');

    Route::resource('mitra', MitraController::class);
    Route::post('update-mitra-status', [MitraController::class, 'updateStatus'])->name('mitra.update-status');
    Route::get('delete-mitra/{id?}', [MitraController::class, 'deleteMitra'])->name('mitra.delete');

    Route::resource('information', InformationController::class);
    Route::post('information/upload', [InformationController::class, 'upload'])->name('information.upload');
    Route::resource('document', DocumentController::class);
    Route::get('document/download/{document}', [DocumentController::class, 'download'])->name('document.download');
    Route::get('document/preview/{document}', [DocumentController::class, 'preview'])->name('document.preview');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::post('update-employee-status', [EmployeeController::class, 'updateStatus'])->name('employees.update-status');

    // CMS Pages
    Route::get('cms-pages', [CmsController::class, 'index'])->name('cms.index');
    Route::post('update-cms-page-status', [CmsController::class, 'update'])->name('cms.update-status');
    Route::match(['get', 'post'], 'add-edit-cms-page/{id?}', [CmsController::class, 'edit'])->name('cms.edit');
    Route::get('delete-cms-page/{id?}', [CmsController::class, 'destroy'])->name('cms.destroy');

    // Subadmins
    Route::get('subadmins', [AdminController::class, 'subadmins'])->name('subadmins');
    Route::post('update-subadmin-status', [AdminController::class, 'updateSubadminStatus'])->name('subadmins.update-status');
    Route::match(['get', 'post'], 'add-subadmin/{id?}', [AdminController::class, 'edit'])->name('subadmins.edit');
    Route::get('delete-subadmin/{id?}', [AdminController::class, 'deleteSubAdmin'])->name('subadmins.delete');

    // Roles Permissions (update-role)
    Route::resource('roles', AdminRoleController::class);
    Route::match(['get', 'post'], 'update-role/{id?}', [AdminController::class, 'updateRole'])->name('update-role');

    // Departments
    Route::resource('departments', DepartmentController::class);
    Route::post('update-department-status', [DepartmentController::class, 'updateStatus'])->name('departments.update-status');

    // Unit Bisnis
    Route::resource('units', UnitController::class);
    Route::post('update-unit-status', [UnitController::class, 'updateStatus'])->name('units.update-status');
    Route::get('delete-unit-image/{id?}', [UnitController::class, 'deleteUnitImage'])->name('units.delete-image');
    
    // Service
    Route::resource('services', ServiceController::class);
    Route::post('update-service-status', [ServiceController::class, 'updateStatus'])->name('services.update-status');
    Route::get('delete-service-image-slide/{id?}', [ServiceController::class, 'deleteSlide'])->name('services.delete-image-slide');

    // Banners
    Route::get('banners', [BannerController::class, 'banners'])->name('banners.index');
    Route::get('delete-banner/{id?}', [BannerController::class, 'deleteBanner'])->name('banners.delete');
    Route::get('delete-banner-image/{id?}', [BannerController::class, 'deleteBannerImage'])->name('banners.delete-image');
    Route::match(['get', 'post'], 'add-edit-banner/{id?}', [BannerController::class, 'edit'])->name('banners.edit');
    Route::post('update-banner-status', [BannerController::class, 'updateStatus'])->name('banners.update-status');
    
    // Account Banks
    Route::resource('banks', AccountBankController::class);
    Route::post('update-account-bank-status', [AccountBankController::class, 'updateStatus'])->name('account-banks.update-status');

    // Coupons
    Route::get('coupons', [CouponController::class, 'coupons'])->name('coupons.index');
    Route::post('update-coupon-status', [CouponController::class, 'updateStatus'])->name('coupons.update-status');
    Route::get('delete-coupon/{id?}', [CouponController::class, 'deletCoupon'])->name('coupons.delete');
    Route::match(['get', 'post'], 'add-edit-coupon/{id?}', [CouponController::class, 'edit'])->name('coupons.edit');

    // Customers
    Route::get('customers', [UserController::class, 'users'])->name('customers.index');
    Route::get('pengunjung', [VisitorController::class, 'index'])->name('pengunjung');

    // Orders
    Route::resource('bookings', BookingController::class);
    Route::post('check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check-availability');
    Route::post('get-price', [BookingController::class, 'getPrice'])->name('bookings.get-price');
    Route::get('bookings/{id}/payment-proof', [BookingController::class, 'showPaymentProof'])->name('bookings.paymentProof');
    Route::get('bookings/export', [BookingController::class, 'export'])->name('bookings.export');
    Route::get('bookings/export-pdf', [BookingController::class, 'exportPdf'])->name('bookings.exportPdf');
    Route::post('update-booking-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    // Calendar
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    Route::get('orders/{id}/detail', [CalendarController::class, 'detailAjax'])->name('calendar.detailAjax');

    // File Manager
    Route::get('files', [FileController::class, 'index'])->name('files.index');
    Route::post('files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::post('files/folder', [FileController::class, 'createFolder'])->name('files.folder');
    Route::post('files/rename', [FileController::class, 'rename'])->name('files.rename');
    Route::post('files/move', [FileController::class, 'move'])->name('files.move');
    Route::get('files/download/{folder?}/{filename}', [FileController::class, 'download'])->name('files.download');
    Route::delete('files/delete', [FileController::class, 'delete'])->name('files.delete');
    Route::get('files/search', [FileController::class, 'search'])->name('files.search');

    // System Info & Logs
    Route::get('system', [SystemInfoController::class, 'index'])->name('system');
    Route::get('system/download', [SystemInfoController::class, 'download'])->name('system.download');
    Route::post('admin/system/clear-log-ajax', [SystemInfoController::class, 'clearLogAjax'])->name('system.clear_log_ajax');

    Route::resource('api-keys', ApiKeyController::class);
    // Database Backup & Restore
    Route::get('database', [DatabaseController::class, 'index'])->name('database');
    Route::get('database/backup', [DatabaseController::class, 'backup'])->name('database.backup');
    Route::PUT('database/restore', [DatabaseController::class, 'restore'])->name('database.restore');
    Route::get('database/download/{fileName}', [DatabaseController::class, 'download'])->name('database.download');
    Route::delete('database/delete', [DatabaseController::class, 'delete'])->name('database.delete');

    // Email Logs
    Route::get('email-logs', function () {
        $logs = \App\Models\EmailLog::latest()->paginate(20);

        return view('admin.email_logs.index', compact('logs'));
    })->name('email-logs.index');

});
