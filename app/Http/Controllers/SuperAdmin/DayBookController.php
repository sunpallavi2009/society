<?php

namespace App\Http\Controllers\SuperAdmin;

use DateTime;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DayBookController extends Controller
{
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.dayBook.index', compact('society', 'societyGuid', 'group'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            $group = $request->query('group');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');
    
            // Check if $fromDate and $toDate are provided in the request
            if (!$fromDate || !$toDate) {
                return response()->json(['error' => 'Both from_date and to_date are required.'], 400);
            }
    
            // Query to retrieve data
            $query = TallyLedger::where('guid', 'like', "$societyGuid%");
                // ->where('primary_group', 'Sundry Debtors')
                // ->whereNotNull('alias1')
                // ->where('alias1', '!=', '');
    
            // Apply date range filter if provided
            $query->whereHas('vouchers', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('voucher_date', [$fromDate, $toDate]);
            });
    
            $members = $query->with(['vouchers' => function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('voucher_date', [$fromDate, $toDate]);
                }])
                ->get()
                ->map(function ($member) use ($fromDate, $toDate) {
    
                    $amount_billed = 0;
                    $amount_received = 0;
                    $opening_balance = 0;
    
                    foreach ($member->vouchers as $voucher) {
                        $date1 = new DateTime($voucher->voucher_date);
                        if ($date1 >= new DateTime($fromDate) && $date1 <= new DateTime($toDate)) {
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
    
                    // Convert $member->this_year_balance to float
                    $this_year_balance = floatval($member->this_year_balance ?? 0);
    
                    $opening_balance = $this_year_balance + (-$amount_billed - $amount_received);
                    $opening_balance = ($opening_balance == 0) ? 0 : -$opening_balance;
    
                    return [
                        'name' => $member->name,
                        'alias1' => $member->alias1,
                        'voucher_details' => $member->vouchers->count(),
                        'voucher_date' => optional($member->vouchers->first())->voucher_date ?? '',
                        'opening_balance' => number_format($opening_balance, 2),
                        'amount_billed' => $amount_billed,
                        'amount_received' => $amount_received,
                        'this_year_balance' => $this_year_balance,
                    ];
                });
    
            // Return DataTables response
            return DataTables::of($members)
                ->addIndexColumn()
                ->make(true);
        }
    }
    
}
