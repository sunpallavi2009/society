<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\BillController;
use App\Http\Controllers\SuperAdmin\MemberController;
use App\Http\Controllers\SuperAdmin\DayBookController;
use App\Http\Controllers\SuperAdmin\ReceiptController;
use App\Http\Controllers\SuperAdmin\SocietyController;
use App\Http\Controllers\SuperAdmin\VoucherController;
use App\Http\Controllers\SuperAdmin\CheckReceiptController;
use App\Http\Controllers\SuperAdmin\VoucherEntryController;
use App\Http\Controllers\SuperAdmin\MemberOutstandingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    
    Route::get('/society', [SocietyController::class, 'index'])->name('society.index');
    Route::get('/society/get-data', [SocietyController::class, 'getData'])->name('society.get-data');
    Route::get('/society/webpanel', [SocietyController::class, 'societyDashboard'])->name('webpanel.index');
    Route::delete('/society/{id}', [SocietyController::class, 'destroy'])->name('society.destroy');

    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/get-data', [MemberController::class, 'getData'])->name('members.get-data');
    
    Route::get('/ledgerDetails', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/ledgerDetails/get-data', [VoucherController::class, 'getData'])->name('vouchers.get-data');

    Route::get('/voucherEntry', [VoucherEntryController::class, 'index'])->name('voucherEntry.index');
    Route::get('/voucherEntry/get-data', [VoucherEntryController::class, 'getData'])->name('voucherEntry.get-data');

    
    Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/get-data', [BillController::class, 'getData'])->name('bills.get-data');

    Route::get('/memberOutstanding', [MemberOutstandingController::class, 'index'])->name('memberOutstanding.index');
    Route::get('/memberOutstanding/get-data', [MemberOutstandingController::class, 'getData'])->name('memberOutstanding.get-data');

    Route::get('/dayBook', [DayBookController::class, 'index'])->name('dayBook.index');
    Route::get('/dayBook/get-data', [DayBookController::class, 'getData'])->name('dayBook.get-data');


    Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('/receipts/get-data', [ReceiptController::class, 'getData'])->name('receipts.get-data');

    Route::get('/checkReceipts', [CheckReceiptController::class, 'index'])->name('checkReceipts.index');
    Route::get('/checkReceipts/get-data', [CheckReceiptController::class, 'getData'])->name('checkReceipts.get-data');

});

require __DIR__.'/auth.php';
