<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('receipts', ReceiptController::class);
    Route::patch('receipts/{receipt}/void', [ReceiptController::class, 'void'])->name('receipts.void');
    Route::get('receipts/export/excel', [ReceiptController::class, 'exportExcel'])->name('receipts.export.excel');
    Route::get('receipts/export/pdf', [ReceiptController::class, 'exportPdf'])->name('receipts.export.pdf');
    Route::get('receipts/{receipt}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');
    Route::get('receipts/{receipt}/pdf/download', [ReceiptController::class, 'pdfDownload'])->name('receipts.pdf.download');
});

require __DIR__.'/settings.php';
