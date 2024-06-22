<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Voucher;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CheckReceiptController extends Controller
{
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.checkReceipts.index', compact('society', 'societyGuid', 'group'));
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

            $query = Voucher::join('tally_ledgers', 'vouchers.ledger_guid', '=', 'tally_ledgers.guid')
                            ->where('vouchers.ledger_guid', 'like', "$ledgerGuid%");

            if (!empty($fromDate) && !empty($toDate)) {
                $query->whereBetween('vouchers.instrument_date', [$fromDate, $toDate]);
            }

            // Filter rows where instrument_number contains only numeric characters
            $vouchers = $query->whereRaw('instrument_number REGEXP "^[0-9]+$"')
                            ->latest('vouchers.created_at')
                            ->get();

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

    //         // Query to fetch vouchers with conditions from related tally_ledgers table
    //         $query = Voucher::join('tally_ledgers', 'vouchers.ledger_guid', '=', 'tally_ledgers.guid')
    //                         ->where('vouchers.ledger_guid', 'like', "$ledgerGuid%");

    //         // Apply condition for TYPE field
    //         $query->where(function($query) {
    //             // $query->where('TYPE', 'Rcpt')
    //             //     ->orWhere('TYPE', 'rcpt')
    //             //     ->orWhere('TYPE', 'receipt');
    //         });

    //         if (!empty($fromDate) && !empty($toDate)) {
    //             $query->whereBetween('vouchers.instrument_date', [$fromDate, $toDate]);
    //         }

    //         $vouchers = $query->latest('vouchers.created_at')->get();

    //         // dd($vouchers);

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

}
