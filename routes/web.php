<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\MemberController;
use App\Http\Controllers\SuperAdmin\BillController;
use App\Http\Controllers\SuperAdmin\SocietyController;
use App\Http\Controllers\SuperAdmin\VoucherController;
use App\Http\Controllers\SuperAdmin\VoucherEntryController;

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

    Route::get('/members', [MemberController::class, 'memberIndex'])->name('members.index');
    Route::get('/members/get-data', [MemberController::class, 'membergetData'])->name('members.get-data');
    
    Route::get('/ledgerDetails', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/ledgerDetails/get-data', [VoucherController::class, 'getData'])->name('vouchers.get-data');

    Route::get('/voucherEntry', [VoucherEntryController::class, 'index'])->name('voucherEntry.index');
    Route::get('/voucherEntry/get-data', [VoucherEntryController::class, 'getData'])->name('voucherEntry.get-data');

    
    Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/get-data', [BillController::class, 'getData'])->name('bills.get-data');

    Route::get('/memberOutstanding', [MemberController::class, 'memberOutstandingIndex'])->name('memberOutstanding.index');
    Route::get('/memberOutstanding/get-data', [MemberController::class, 'memberOutstandingGetData'])->name('memberOutstanding.get-data');


});

require __DIR__.'/auth.php';
