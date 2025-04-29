<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PurchaseOrderController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/admin/dashboard');
    }
    return redirect('/admin/login');
});

// Redirect /listquotation to /admin/listquotation to fix 404
Route::get('/listquotation', function () {
    return redirect('/admin/listquotation');
});


// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Inquiry Routes
    Route::prefix('admin')->group(function () {
        Route::get('/listinquiry', [InquiryController::class, 'listInquiries'])->name('admin.listinquiry');
        Route::post('/inquiries/{id}/update-status', [InquiryController::class, 'updateStatus'])->name('inquiries.updateStatus');
        Route::delete('/inquiries/{id}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

        // Edit and update inquiry
        Route::get('/inquiries/{id}/edit', [InquiryController::class, 'edit'])->name('inquiries.edit');
        Route::put('/inquiries/{id}', [InquiryController::class, 'update'])->name('inquiries.update');
    });

    // Quotation Routes
    Route::prefix('admin')->group(function () {
        Route::post('/quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');
Route::post('/quotations/{quotation}/due-date', [QuotationController::class, 'updateDueDate'])->name('quotations.update-due-date');
Route::get('/quotations/{quotation}/download', [QuotationController::class, 'download'])->name('quotations.download');
Route::get('/quotations/{quotation}/view', [QuotationController::class, 'viewDocument'])->name('quotations.view');
Route::post('/quotations/{quotation}/resend-email', [QuotationController::class, 'resendEmail'])->name('quotations.resend-email');
Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');

        // Add GET route for listing quotations
        Route::get('/listquotation', [QuotationController::class, 'index'])->name('admin.listquotation');

        // Add DELETE route for deleting quotations
        Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

        // Add PUT route for updating quotations
        Route::put('/quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    });

    // Payment Page
    Route::get('/admin/payment', function () {
        return view('admin.payment');
    })->name('admin.payment');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Purchase Order Routes
    Route::get('/purchaseorder', [PurchaseOrderController::class, 'index'])->name('purchaseorder.index');
    Route::get('/admin/purchaseorderlist', [PurchaseOrderController::class, 'list'])->name('purchaseorder.list');
    Route::post('/purchaseorder/search', [PurchaseOrderController::class, 'searchQuotation'])->name('purchaseorder.search');
    Route::post('/purchaseorder', [PurchaseOrderController::class, 'store'])->name('purchaseorder.store');

    // Purchase Order delete and status update routes
    Route::delete('/admin/purchaseorder/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchaseorder.destroy');
    Route::post('/admin/purchaseorder/{id}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchaseorder.updateStatus');
});

// Public Inquiry Form Routes (User Side)
Route::controller(InquiryController::class)->group(function () {
    Route::get('/user/inquiryform', 'index')->name('inquiries.form');
    Route::post('/user/inquiryform', 'store')->name('inquiries.store');
    Route::get('/inquiries/search-customers', 'searchCustomers')->name('inquiries.search-customers');
});

// Test Email Route (Only for Local Development)
if (app()->environment('local')) {
    Route::get('/test-email', function () {
        $quotation = new \App\Models\Quotation(); 
        $quotation->customer_name = 'John Doe';
        $quotation->email = 'your_email@gmail.com';

        Mail::to($quotation->email)->send(new \App\Mail\QuotationMail($quotation));
        return 'Test email sent!';
    })->middleware('auth');
}
