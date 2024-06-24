<?php

namespace App\Http\Controllers\SuperAdmin;

use Log;
use DateTime;
use App\Models\Voucher;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DayBookController extends Controller
{
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $ledgerGuid = $request->query('ledger_guid');
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        // dd($society);
        // $voucherEntries = VoucherEntry::all();
        // dd($voucherEntries->ledger_guid);
        return view('superadmin.dayBook.dayBook', compact('society', 'societyGuid', 'group', 'ledgerGuid'));
    }
    
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');

            
    
            // Validate input dates
            if (!$fromDate || !$toDate) {
                return response()->json(['error' => 'Both from_date and to_date are required.'], 400);
            }
    
            $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
            if (!$society) {
                return response()->json(['message' => 'Society not found'], 404);
            }
    
            // Query to fetch vouchers with conditions from related tables
            $vouchers = Voucher::whereBetween('voucher_date', [$fromDate, $toDate])
                                ->with(['voucherEntries'])
                                ->select([
                                    'id',
                                    'voucher_date',
                                    'type',
                                    'voucher_number',
                                    'amount',
                                ])
                                ->get();
    
            // Calculate totals
            $query = $vouchers->map(function ($voucher) {
                $debit_total = $voucher->voucherEntries->where('entry_type', 'debit')->sum('amount');
                $credit_total = $voucher->voucherEntries->where('entry_type', 'credit')->sum('amount');
    
                $ledgerEntries = $voucher->voucherEntries->last()->ledger;
                $ledgerguidEntries = $voucher->voucherEntries->last()->ledger_guid;
    
                return [
                    'voucher_date' => $voucher->voucher_date,
                    'ledger' => $ledgerEntries,
                    'ledger_guid' => $ledgerguidEntries,
                    'type' => $voucher->type,
                    'voucher_number' => $voucher->voucher_number,
                    'amount' => $voucher->amount,
                    'debit_total' => number_format(abs($debit_total), 2, '.', ''),
                    'credit_total' => number_format(abs($credit_total), 2, '.', ''),
                ];
            });
    
            return DataTables::of($query)
                            ->addIndexColumn()
                            ->addColumn('voucher_date', function($row) {
                                if ($row['voucher_date']) {
                                    return date('d-m-Y', strtotime($row['voucher_date']));
                                }
                                return 'Invalid date';
                            })
                            ->rawColumns(['voucher_date'])
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
    
    //         // Validate input dates
    //         if (!$fromDate || !$toDate) {
    //             return response()->json(['error' => 'Both from_date and to_date are required.'], 400);
    //         }
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $ledgerGuid = $society->guid;
    
    //         // Query to fetch vouchers with conditions from related tables
    //         $query = Voucher::whereBetween('voucher_date', [$fromDate, $toDate])
    //                         ->select([
    //                             'voucher_date',
    //                             'type',
    //                             'voucher_number',
    //                             'amount',
    //                         ])
    //                         ->get();
    
    //         return DataTables::of($query)
    //                         ->addIndexColumn()
    //                         ->addColumn('voucher_date', function($row) {
    //                             return $row->voucher_date ? date('d-m-Y', strtotime($row->voucher_date)) : '';
    //                         })
    //                         ->rawColumns(['voucher_date'])
    //                         ->make(true);
    //     }
    
    //     return abort(403, 'Unauthorized action.');
    // }
    

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $fromDate = $request->query('from_date');
    //         $toDate = $request->query('to_date');
    
    //         // Validate input dates
    //         if (!$fromDate || !$toDate) {
    //             return response()->json(['error' => 'Both from_date and to_date are required.'], 400);
    //         }
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $ledgerGuid = $society->guid;
    
    //         // Query to fetch vouchers with conditions from related tables
    //         $query = Voucher::join('tally_ledgers', 'vouchers.ledger_guid', '=', 'tally_ledgers.guid')
    //                         ->join('voucher_entries', 'vouchers.id', '=', 'voucher_entries.voucher_id')
    //                         ->where('vouchers.ledger_guid', 'like', "$ledgerGuid%")
    //                         ->whereBetween('vouchers.voucher_date', [$fromDate, $toDate])
    //                         ->select([
    //                             'vouchers.voucher_date as instrument_date',
    //                             'tally_ledgers.name as ledger_name',
    //                             'tally_ledgers.alias1',
    //                             'vouchers.voucher_number',
    //                             'vouchers.narration',
    //                             'vouchers.credit_ledger',
    //                             'voucher_entries.ledger as ledger', // Fetch ledger_id from voucher_entries
    //                             DB::raw("CONCAT(vouchers.credit_ledger, ' ', vouchers.narration) as combined_field")
    //                         ])
    //                         ->get();


    //                         // dd($query);
    
    //         return DataTables::of($query)
    //             ->addIndexColumn()
    //             ->editColumn('instrument_amount', function($row) {
    //                 $amount = abs($row->instrument_amount); // Remove minus sign if present
    //                 return number_format($amount, 2); // Format as currency or numeric
    //             })
    //             ->make(true);
    //     }
    
    //     return abort(403, 'Unauthorized action.');
    // }
    

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $group = $request->query('group');
    //         $fromDate = $request->query('from_date');
    //         $toDate = $request->query('to_date');
    
    //         // Check if $fromDate and $toDate are provided in the request
    //         if (!$fromDate || !$toDate) {
    //             return response()->json(['error' => 'Both from_date and to_date are required.'], 400);
    //         }
    
    //         // Query to retrieve data
    //         $query = TallyLedger::where('guid', 'like', "$societyGuid%");
    //             // ->where('primary_group', 'Sundry Debtors')
    //             // ->whereNotNull('alias1')
    //             // ->where('alias1', '!=', '');
    
    //         // Apply date range filter if provided
    //         $query->whereHas('vouchers', function ($q) use ($fromDate, $toDate) {
    //             $q->whereBetween('voucher_date', [$fromDate, $toDate]);
    //         });
    
    //         $members = $query->with(['vouchers' => function ($query) use ($fromDate, $toDate) {
    //                 $query->whereBetween('voucher_date', [$fromDate, $toDate]);
    //             }])
    //             ->get()
    //             ->map(function ($member) use ($fromDate, $toDate) {
    //                 return [
    //                     'guid' => $member->guid,
    //                     'name' => $member->name,
    //                     'alias1' => $member->alias1,
    //                     'type' => optional($member->vouchers->first())->type ?? '',
    //                     'voucher_number' => optional($member->vouchers->first())->voucher_number ?? '',
    //                     'voucher_date' => optional($member->vouchers->first())->voucher_date ?? '',
    //                     'debit_total' => $member->alias1,
    //                     'credit_total' => $member->alias1,
    //                     // Optionally, you can include other fields if needed
    //                 ];
    //             });
    
    //         // Return DataTables response
    //         return DataTables::of($members)
    //             ->addIndexColumn()
    //             ->make(true);
                
    //     }
    // }
    
    
}
