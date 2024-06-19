<?php

namespace App\Http\Controllers\SuperAdmin;

// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use App\Models\Voucher;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $ledgerGuid = $request->query('ledger_guid');
        $members = TallyLedger::where('guid', $ledgerGuid)->get();
        $societyGuid = explode('-', $ledgerGuid)[0];
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
        return view('superadmin.vouchers.index', compact('ledgerGuid', 'society', 'members'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $ledgerGuid = $request->query('ledger_guid');

            $societyGuid = explode('-', $ledgerGuid)[0];
            $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();


            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');

            $query = Voucher::where('ledger_guid', $ledgerGuid);

            if ($fromDate) {
                $query->whereDate('voucher_date', '>=', Carbon::parse($fromDate));
            }
            
            if ($toDate) {
                $query->whereDate('voucher_date', '<=', Carbon::parse($toDate));
            }
            
            // $vouchers = $query->latest()->get();
            $vouchers = $query->orderBy('voucher_date', 'asc')->get();

            //dd($vouchers);

            
            return DataTables::of($vouchers)
                ->editColumn('voucher_date', function ($voucher) {
                    $date = Carbon::parse($voucher->voucher_date);
                    return $date->format('Y-m-d');
                })
                ->addColumn('debit', function ($voucher) {
                    return $voucher->amount < 0 ? number_format(abs($voucher->amount), 2, '.', '') : '';
                })
                ->addColumn('credit', function ($voucher) {
                    return $voucher->amount >= 0 ? number_format($voucher->amount, 2, '.', '') : '';
                })
                           
                ->addIndexColumn()
                ->with(['societyGuid' => $societyGuid]) 
                ->make(true);
        }
    }
}
