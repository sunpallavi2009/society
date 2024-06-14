<?php

namespace App\Http\Controllers\SuperAdmin;

use DateTime;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.bills.index', compact('society', 'societyGuid', 'group'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            $group = $request->query('group');
            $billDate = $request->query('bill_date');
    
            // Check if $billDate is provided in the request
            if (!$billDate) {
                return response()->json(['error' => 'bill_date is required.'], 400);
            }
    
            // Query to retrieve data
            $query = TallyLedger::where('guid', 'like', "$societyGuid%")
                ->where('primary_group', 'Sundry Debtors')
                ->whereNotNull('alias1')
                ->where('alias1', '!=', '');
    
            // Apply date filter if provided
            $query->whereHas('vouchers', function ($q) use ($billDate) {
                $q->whereDate('voucher_date', $billDate);
            });
    
            $members = $query->with(['vouchers' => function ($query) use ($billDate) {
                    $query->whereDate('voucher_date', $billDate);
                }])
                ->get()
                ->map(function ($member) use ($billDate) {
                    $amount_billed = 0;
                    $amount_received = 0;
                    $opening_balance = 0;
    
                    foreach ($member->vouchers as $voucher) {
                        $date = new DateTime($voucher->voucher_date);
                        if ($date >= new DateTime($billDate)) {
                            if ($voucher->amount < 0) {
                                $amount_billed += $voucher->amount;
                            } else {
                                $amount_received += $voucher->amount;
                            }
                        }
                    }
    
                    $lastVoucher = $member->vouchers->last();
                    if ($lastVoucher) {
                        $opening_balance = $lastVoucher->BAL ?? 0;
                    }
    
                    $opening_balance = $member->this_year_balance + (-$amount_billed - $amount_received);
                    $opening_balance = ($opening_balance == 0) ? 0 : -$opening_balance;


                    $outstanding = $opening_balance - $amount_billed;


                     // Apply transformation to this_year_balance
                    // $this_year_balance = $member->this_year_balance ?? 0;
                    // if (is_numeric($this_year_balance) && $this_year_balance != 0) {
                    //     $this_year_balance = -1 * floatval($this_year_balance);
                    // }
    
                    return [
                        'name' => $member->name,
                        'alias1' => $member->alias1,
                        'voucher_number' => optional($member->vouchers->first())->voucher_number ?? 0,
                        'voucher_date' => optional($member->vouchers->first())->voucher_date ?? '',
                        'opening_balance' => number_format($opening_balance, 2),
                        'amount_billed' => number_format(abs($amount_billed), 2), 
                        'outstanding' => number_format($outstanding, 2),
                        'amount_received' => $amount_received,
                        'this_year_balance' => $member->this_year_balance ?? 0,
                        'guid' => $member->guid, 
                    ];
                });
    
            // Return DataTables response
            return DataTables::of($members)
                ->addIndexColumn()
                ->make(true);
        }
    }
    


    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $group = $request->query('group');
    //         $billDate = $request->query('bill_date');
    
    //         // Check if $billDate is provided in the request
    //         if (!$billDate) {
    //             return response()->json(['error' => 'bill_date is required.'], 400);
    //         }
    
    //         // Query to retrieve data
    //         $query = TallyLedger::where('guid', 'like', "$societyGuid%")
    //             ->where('primary_group', 'Sundry Debtors')
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '');
    
    //         // Apply date filter if provided
    //         $query->whereHas('vouchers', function ($q) use ($billDate) {
    //             $q->whereDate('voucher_date', $billDate);
    //         });
    
    //         $members = $query->with(['vouchers' => function ($query) use ($billDate) {
    //                 $query->whereDate('voucher_date', $billDate);
    //             }])
    //             ->get()
    //             ->map(function ($member) use ($billDate) {
    //                 $amount_billed = 0;
    //                 $amount_received = 0;
    //                 $opening_balance = 0;
    
    //                 foreach ($member->vouchers as $voucher) {
    //                     $date = new DateTime($voucher->voucher_date);
    //                     if ($date >= new DateTime($billDate)) {
    //                         if ($voucher->amount < 0) {
    //                             $amount_billed += $voucher->amount;
    //                         } else {
    //                             $amount_received += $voucher->amount;
    //                         }
    //                     }
    //                 }
    
    //                 $lastVoucher = $member->vouchers->last();
    //                 if ($lastVoucher) {
    //                     $opening_balance = $lastVoucher->BAL ?? 0;
    //                 }
    
    //                 $opening_balance = $member->this_year_balance + (-$amount_billed - $amount_received);
    //                 $opening_balance = ($opening_balance == 0) ? 0 : -$opening_balance;
    
    //                 return [
    //                     'name' => $member->name,
    //                     'alias1' => $member->alias1,
    //                     'voucher_number' => optional($member->vouchers->first())->voucher_number ?? 0,
    //                     'voucher_date' => optional($member->vouchers->first())->voucher_date ?? '',
    //                     'opening_balance' => number_format($opening_balance, 2),
    //                     'amount_billed' => $amount_billed,
    //                     'amount_received' => $amount_received,
    //                     'this_year_balance' => $member->this_year_balance ?? 0,
    //                 ];
    //             });
    
    //         // Return DataTables response
    //         return DataTables::of($members)
    //         ->addColumn('outstanding', function ($member) {
    //             $openingBalance = floatval(str_replace(',', '', $member['opening_balance']));
    //             $amount_billed = floatval(str_replace(',', '', $member['amount_billed']));
    //             $outstanding = $openingBalance + $amount_billed;
    //             return number_format($outstanding, 2);
    //         })
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }
    

    

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $group = $request->query('group');
    //         $billDate = $request->query('bill_date');

    //         $query = TallyLedger::where('guid', 'like', "$societyGuid%")
    //             ->where('primary_group', 'Sundry Debtors')
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '');
            
    //         // Apply date range filter if provided
    //         if ($billDate) {
    //             $query->whereHas('vouchers', function ($q) use ($billDate) {
    //                 $q->whereDate('voucher_date', '=', $billDate);
    //             });
    //         }
            
    //         // Fetch data with relations
    //         $members = $query->with(['vouchers' => function ($query) use ($billDate) {
    //             if ($billDate) {
    //                 $query->whereDate('voucher_date', '=', $billDate);
    //             }
    //         }])
    //         ->get()
    //         ->map(function ($member) {
    //             // Calculate opening balance, amount, instrument_amount, and other necessary fields
    //             $this_year_balance = $member->this_year_balance;
    //             $amount = optional($member->vouchers->first())->amount ?? 0;
    //             $instrument_amount = optional($member->vouchers->first(function ($voucher) {
    //                 return !empty($voucher->instrument_amount);
    //             }))->instrument_amount ?? 0;
                
    //             $opening_balance = $this_year_balance + (-($amount) - ($instrument_amount));
    //             $opening_balance = ($opening_balance == 0) ? 0 : -$opening_balance;
                
    //             $voucher_number = optional($member->vouchers->first())->voucher_number ?? 0;


    //             $amount = abs($amount);
    //             $instrument_amount = abs($instrument_amount);
                
    //             return [
    //                 'name' => $member->name,
    //                 'alias1' => $member->alias1,
    //                 'voucher_number' => $voucher_number,
    //                 'voucher_date' => optional($member->vouchers->first())->voucher_date ?? '',
    //                 'opening_balance' => number_format($opening_balance, 2),
    //                 'amount' => $amount,
    //                 'instrument_amount' => $instrument_amount,
    //                 'this_year_balance' => $this_year_balance,
    //             ];
    //         });
            
    //         return DataTables::of($members)
    //             ->addColumn('outstanding', function ($member) {
    //                 $openingBalance = floatval(str_replace(',', '', $member['opening_balance']));
    //                 $amount = floatval(str_replace(',', '', $member['amount']));
    //                 $outstanding = $openingBalance + $amount;
    //                 return number_format($outstanding, 2);
    //             })
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }
    
    
    
    

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         // Retrieve the necessary parameters from the request
    //         $societyGuid = $request->query('guid');
    //         $group = $request->query('group');
    //         $billDate = $request->query('billDate'); // Ensure 'billDate' matches the parameter name from the JavaScript code

    //         // Find the society based on the provided guid
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();

    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }

    //         // Initialize the query for TallyLedger with the necessary filters
    //         $query = TallyLedger::where('guid', 'like', $society->guid . '%')
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '');

    //         // Filter data based on the bill_date parameter
    //         if ($billDate) {
    //             // Assuming 'voucher_date' is the column in the 'vouchers' table to filter
    //             $query->whereHas('vouchers', function ($q) use ($billDate) {
    //                 $q->whereDate('voucher_date', '=', $billDate);
    //             });
    //         }

    //         // Initialize the results array
    //         $results = [];

    //         // Process the query results in chunks
    //         $query->chunk(100, function ($members) use (&$results) {
    //             foreach ($members as $member) {
    //                 // Populate additional member data
    //                 $member->first_voucher_date = $member->firstVoucherDate();
    //                 $member->voucher_number = $member->vouchers->first()->voucher_number ?? '';
    //                 $member->amount = $member->vouchers->first()->amount ?? '';
    //                 $results[] = $member;
    //             }
    //         });

    //         // Return the results as a DataTable response
    //         return DataTables::of($results)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }

    

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $group = $request->query('group');
    //         $billDate = $request->query('bill_date'); // Retrieve bill_date parameter
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $query = TallyLedger::where('guid', 'like', $society->guid . '%')
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '');
    
    //         // Filter data based on bill_date
    //         if ($billDate) {
    //             // Assuming voucher_date is the column in vouchers table to filter
    //             $query->whereHas('vouchers', function ($q) use ($billDate) {
    //                 $q->whereDate('voucher_date', '=', $billDate);
    //             });
    //         }
    
    //         $members = $query->withCount('vouchers')
    //             ->with('vouchers')
    //             ->latest()
    //             ->paginate(50)
    //             // ->get()
    //             ->map(function ($member) {
    //                 // Assuming first_voucher_date is a dynamic property/method
    //                 $member->first_voucher_date = $member->firstVoucherDate();
    //                 $member->voucher_number = $member->vouchers->first()->voucher_number ?? '';
    //                 $member->amount = $member->vouchers->first()->amount ?? '';
    //                 return $member;
    //             });
    
    //         return DataTables::of($members)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $group = $request->query('group');
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $query = TallyLedger::where('guid', 'like', $society->guid . '%')
    //                                 ->whereNotNull('alias1')
    //                                 ->where('alias1', '!=', '');

    
    //         $members = $query->withCount('vouchers')
    //             ->with('vouchers')
    //             ->latest()
    //             ->get()
    //             ->map(function($member) {
    //                 $member->first_voucher_date = $member->firstVoucherDate();
    //                 $member->voucher_number = $member->vouchers->first()->voucher_number ?? '';
    //                 $member->amount = $member->vouchers->first()->amount ?? '';
    //                 return $member;
    //             });
    
    //         return DataTables::of($members)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }
}
