<?php

namespace App\Http\Controllers\SuperAdmin;

use DateTime;
use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ReceiptController extends Controller
{
    
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.receipts.index', compact('society', 'societyGuid', 'group'));
    }


    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');
    
            $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
            if (!$society) {
                return response()->json(['message' => 'Society not found'], 404);
            }
    
            $ledgerGuid = $society->guid;
    
            // Query to fetch the latest voucher IDs for each unique alias1
            $subQuery = Voucher::join('tally_ledgers', 'vouchers.ledger_guid', '=', 'tally_ledgers.guid')
                                ->where('vouchers.ledger_guid', 'like', "$ledgerGuid%")
                                ->where(function($query) {
                                    $query->where('TYPE', 'Rcpt')
                                        ->orWhere('TYPE', 'rcpt')
                                        ->orWhere('TYPE', 'receipt');
                                })
                                ->when(!empty($fromDate) && !empty($toDate), function($query) use ($fromDate, $toDate) {
                                    $query->whereBetween('vouchers.instrument_date', [$fromDate, $toDate]);
                                })
                                ->selectRaw('MAX(vouchers.id) as voucher_id, tally_ledgers.alias1')
                                ->groupBy('tally_ledgers.alias1');
    
            // Join the subquery to get the full voucher records
            $vouchers = Voucher::join('tally_ledgers', 'vouchers.ledger_guid', '=', 'tally_ledgers.guid')
                               ->joinSub($subQuery, 'latest_vouchers', function ($join) {
                                   $join->on('vouchers.id', '=', 'latest_vouchers.voucher_id');
                               })
                               ->select('vouchers.*', 'tally_ledgers.alias1')
                               ->latest('vouchers.created_at')
                               ->get();
    
            return DataTables::of($vouchers)
                ->addIndexColumn()
                ->addColumn('ledger', function ($voucher) {
                    return $voucher->tallyLedger->name ?? '';
                })
                ->addColumn('alias1', function ($voucher) {
                    return $voucher->tallyLedger->alias1 ?? '';
                })
                ->make(true);
        }
    
        return abort(403, 'Unauthorized action.');
    }


}
