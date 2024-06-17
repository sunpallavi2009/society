<?php

namespace App\Http\Controllers\SuperAdmin;

use DateTime;
use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
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
   
//     public function getData(Request $request)
// {
//     if ($request->ajax()) {
//         $societyGuid = $request->query('guid');
//         $fromDate = $request->query('from_date');
//         $toDate = $request->query('to_date');

//         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();

//         if (!$society) {
//             return response()->json(['message' => 'Society not found'], 404);
//         }

//         $ledgerGuid = $society->guid;

//         $query = Voucher::where('ledger_guid', 'like', "$ledgerGuid%")
//                         ->where('primary_group', 'Sundry Debtors')
//                         ->whereNotNull('alias1')
//                         ->where('alias1', '!=', '');

//         if (!empty($fromDate) && !empty($toDate)) {
//             $query->whereBetween('instrument_date', [$fromDate, $toDate]);
//         }

//         $vouchers = $query->latest()->get();

//         return DataTables::of($vouchers)
//             ->addIndexColumn()
//             ->addColumn('ledger_name', function ($voucher) {
//                 return $voucher->tallyLedger->name ?? '';
//             })
//             ->addColumn('alias1', function ($voucher) {
//                 return $voucher->tallyLedger->alias1 ?? '';
//             })
//             ->make(true);
//     }

//     return abort(403, 'Unauthorized action.');
// }

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $fromDate = $request->query('from_date');
    //         $toDate = $request->query('to_date');
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $ledgerGuid = $society->guid;
    
    //         // $query = Voucher::where('ledger_guid', 'like', "$ledgerGuid%");

    //         $query = Voucher::where('ledger_guid', 'like', "$ledgerGuid%")
    //         ->where(function ($q) {
    //             $q->whereRaw('LOWER(TYPE) = ?', ['rcpt'])
    //               ->orWhereRaw('LOWER(TYPE) = ?', ['receipt']);
    //         });
    
    //         if (!empty($fromDate) && !empty($toDate)) {
    //             $query->whereBetween('instrument_date', [$fromDate, $toDate]);
    //         }
    
    //         $vouchers = $query->latest()->get();


    //         //dd($vouchers);
    
    //         return DataTables::of($vouchers)
    //             ->addIndexColumn()
    //             ->addColumn('ledger_name', function ($voucher) {
    //                 return $voucher->tallyLedger->name ?? '';
    //             })
    //             ->addColumn('alias1', function ($voucher) {
    //                 return $voucher->tallyLedger->alias1 ?? '';
    //             })
    //             ->make(true);
    //     }
    
    //     return abort(403, 'Unauthorized action.');
    // }
    
    
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

        // Query to fetch vouchers with conditions from related tally_ledgers table
        $query = Voucher::join('tally_ledgers', 'vouchers.ledger_guid', '=', 'tally_ledgers.guid')
                        ->where('vouchers.ledger_guid', 'like', "$ledgerGuid%");
                        // ->whereIn('tally_ledgers.primary_group', ['Sundry Debtors']);
                        // ->where('primary_group', '!=', 'Sundry Debtors');

                        // ->whereNotNull('tally_ledgers.alias1')
                        // ->where('tally_ledgers.alias1', '!=', '');

        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('vouchers.instrument_date', [$fromDate, $toDate]);
        }

        $vouchers = $query->latest('vouchers.created_at')->get();

        return DataTables::of($vouchers)
            ->addIndexColumn()
            ->addColumn('ledger_name', function ($voucher) {
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
