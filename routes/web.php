<?php

use App\Http\Controllers\BerandaController;
use Illuminate\Support\Facades\Route;
use App\Exports\PeminjamanBarangExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanExport;

Route::get('/', function () {
    return view('index');
});

Route::get('/', [BerandaController::class, 'index'])->name('beranda');


Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/ajukan-peminjaman', [BerandaController::class, 'showForm'])->name('form.peminjaman');
Route::post('/ajukan-peminjaman', [BerandaController::class, 'store'])->name('form.peminjaman.store');

Route::get('/tracking', [BerandaController::class, 'showTracking'])->name('tracking.form');
Route::post('/tracking', [BerandaController::class, 'processTracking'])->name('tracking.process');
Route::get('/tracking/{kode}', [BerandaController::class, 'showTrackingResult'])->name('tracking.result');

Route::get('/unduh-surat/{kode}', [BerandaController::class, 'unduhSurat'])->name('unduh.surat');

Route::get('/pinjam-barang', [BerandaController::class, 'showFormBarang'])->name('form.barang');
Route::post('/pinjam-barang', [BerandaController::class, 'storeBarang'])->name('form.barang.store');

// Tracking barang
Route::get('/tracking-barang', [BerandaController::class, 'showTrackingBarang'])->name('tracking.barang.form');
Route::post('/tracking-barang', [BerandaController::class, 'processTrackingBarang'])->name('tracking.barang.process');
Route::get('/tracking-barang/{kode}', [BerandaController::class, 'showTrackingResultBarang'])->name('tracking.barang.result');

// Unduh surat barang
Route::get('/unduh-surat-barang/{kode}', [BerandaController::class, 'unduhSuratBarang'])->name('unduh.surat.barang');

Route::get('/export-peminjaman-barang', function () {
    return Excel::download(
        new PeminjamanBarangExport(),
        'peminjaman-barang.xlsx'
    );
})->name('export.peminjaman-barang');


Route::get('/export-peminjaman-ruangan', function () {
    return Excel::download(
        new PeminjamanExport(),
        'peminjaman-ruangan.xlsx'
    );
})->name('export.peminjaman-ruangan');