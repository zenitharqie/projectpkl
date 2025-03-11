<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\DashboardController;


// Halaman Utama
Route::get('/', function () {
    return redirect('/admin/login');
});

// Halaman Login
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);


// Route untuk dashboard admin

// Dashboard Admin (Hanya bisa diakses setelah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/quotation', function () {
        return view('admin.quotation');
    });

    Route::get('/admin/listinquiry', function () {
        return view('admin.listinquiry');
    });

    Route::get('/admin/payment', function () {
        return view('admin.payment');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Halaman Inquiry User (Tidak butuh autentikasi)
Route::get('/user/inquiryform', function () {
    return view('user.inquiryform');
});

// Form Inquiry (Menampilkan form dan menyimpan data)
Route::get('/user/inquiryform', [InquiryController::class, 'index'])->name('inquiries.index');
Route::post('/user/inquiryform', [InquiryController::class, 'store'])->name('inquiries.store');

// Route untuk menampilkan list inquiry di admin dashboard
Route::get('/admin/listinquiry', [InquiryController::class, 'listInquiries'])->name('admin.listinquiry');

// Route untuk mengupdate status inquiry
Route::post('/inquiries/{id}/update-status', [InquiryController::class, 'updateStatus'])->name('inquiries.updateStatus');

Route::get('/inquiry/{id}', function ($id) {
    $inquiry = Inquiry::find($id);
    return response()->json($inquiry);
});

Route::delete('/inquiries/{id}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');