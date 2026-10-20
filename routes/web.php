<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EscrituraController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('escrituras', EscrituraController::class);
    Route::get('escrituras/export/excel', [EscrituraController::class, 'exportExcel'])->name('escrituras.export.excel');
    Route::get('escrituras/export/pdf', [EscrituraController::class, 'exportPdf'])->name('escrituras.export.pdf');
    Route::resource('receipts', ReceiptController::class);
    Route::patch('receipts/{receipt}/void', [ReceiptController::class, 'void'])->name('receipts.void');
    Route::get('receipts/export/excel', [ReceiptController::class, 'exportExcel'])->name('receipts.export.excel');
    Route::get('receipts/export/pdf', [ReceiptController::class, 'exportPdf'])->name('receipts.export.pdf');
    Route::get('receipts/{receipt}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');
    Route::get('receipts/{receipt}/pdf/download', [ReceiptController::class, 'pdfDownload'])->name('receipts.pdf.download');

    Route::get('notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('notes/export/excel', [NoteController::class, 'exportExcel'])->name('notes.export.excel');
    Route::get('notes/export/pdf', [NoteController::class, 'exportPdf'])->name('notes.export.pdf');
    Route::get('notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
    Route::patch('notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
});

require __DIR__.'/settings.php';
