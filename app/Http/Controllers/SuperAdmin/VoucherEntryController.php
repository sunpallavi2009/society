<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Voucher;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VoucherEntryController extends Controller
{
    public function index(Request $request)
    {
        $voucherId = $request->query('voucher_id');
        $ledgerGuid = $request->query('ledger_guid');

        // Fetch the voucher entry based on the voucher_id
        $voucher = Voucher::findOrFail($voucherId);

        // Retrieve the ledger GUID associated with the voucher entry
        $ledgerGuid = $voucher->ledger_guid;

        // Use the ledger GUID to find the society associated with it
        $societyGuid = explode('-', $ledgerGuid)[0]; 
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();

        $members = TallyLedger::where('guid', 'like', "$ledgerGuid%")->first(); 
        $vouchers = Voucher::where('id', $voucherId)->get();

        // Pass the society name to the view
        return view('superadmin.voucherentries.index', compact('voucherId','society','members','vouchers'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $voucherId = $request->query('voucher_id');
            $voucherEntries = VoucherEntry::where('voucher_id', $voucherId)->latest()->get();

            return DataTables::of($voucherEntries)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
