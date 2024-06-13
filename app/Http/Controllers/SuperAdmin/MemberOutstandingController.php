<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class MemberOutstandingController extends Controller
{
    
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.memberOutstanding.index', compact('society', 'societyGuid', 'group'));
    }


    
    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         $group = $request->query('group');
    //         $fromDate = $request->query('from_date');
    //         $toDate = $request->query('to_date');
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $query = TallyLedger::where('guid', 'like', $society->guid . '%')
    //             ->where('primary_group', 'Sundry Debtors')
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '')
    //             ->orderBy('alias1', 'ASC');
    
    //             if ($fromDate && $toDate) {
    //                 $query->whereHas('vouchers', function ($q) use ($fromDate, $toDate) {
    //                     $q->whereBetween('voucher_date', [$fromDate, $toDate]);
    //                 });
    //             }
    
    //         $members = $query->withCount('vouchers')
    //             ->with('vouchers')
    //             ->latest()
    //             ->get()
    //             ->map(function ($member) {
    //                 // Assuming first_voucher_date is a dynamic property/method
    //                 $member->first_voucher_date = $member->firstVoucherDate();
    //                 $member->voucher_number = $member->vouchers->first()->voucher_number ?? '';
    //                 $member->amount = $member->vouchers->first()->amount ?? '';
    //                 // $member->voucher_date = $member->vouchers->first()->voucher_date ?? '';
    //                 $member->voucher_date = $member->vouchers->first()->voucher_date ? Carbon::parse($member->vouchers->first()->voucher_date)->format('Y-m-d') : '';

    //                 return $member;
    //             });
    
    //         return DataTables::of($members)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }

    

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            $group = $request->query('group');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');
    
            // Query to retrieve data
            $query = TallyLedger::where('guid', 'like', "$societyGuid%")
                ->where('primary_group', 'Sundry Debtors')
                ->whereNotNull('alias1')
                ->where('alias1', '!=', '');
    
            // Apply date range filter if provided
            if ($fromDate && $toDate) {
                $query->whereHas('vouchers', function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('voucher_date', [$fromDate, $toDate]);
                });
            }
    
            // Fetch data with relations
            $members = $query->with(['vouchers' => function ($query) use ($fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $query->whereBetween('voucher_date', [$fromDate, $toDate]);
                    }
                }])
                ->get()
                ->map(function ($member) {
                    // Calculate opening balance based on vouchers within the date range
                    // $member->opening_balance = $member->vouchers->sum('amount'); // Adjust this based on your application logic
    
                    // Fetch instrument_amount based on voucher_date
                    $member->instrument_amount = optional($member->vouchers->first(function ($voucher) {
                        return !empty($voucher->instrument_amount);
                    }))->instrument_amount ?? 0; // Adjust if instrument_amount can be null
    
                    // $opening_balance = 0;

                    // // Calculate opening balance based on vouchers within the date range
                    // foreach ($member->vouchers as $voucher) {
                    //     // Adjust this based on your application logic to sum up relevant amounts
                    //     $opening_balance += $voucher->amount;
                    // }


                    $this_year_balance = $member->this_year_balance;
                    $amount = optional($member->vouchers->first())->amount ?? '';
                    $instrument_amount = $member->instrument_amount;



                    $opening_balance = $this_year_balance + (-$amount - $instrument_amount);
                    
                    $opening_balance = ($opening_balance == 0) ? 0 : -$opening_balance;


                    $voucherDates = $member->vouchers->groupBy('voucher_date')->map->count();

                    $totalVouchersCount = $member->vouchers->count();

    
                    return [
                        'name' => $member->name,
                        'alias1' => $member->alias1,
                        // 'voucher_number' => optional($member->vouchers->first())->voucher_number ?? '',
                        'voucher_details' => $totalVouchersCount,
                        'voucher_date' => optional($member->vouchers->first())->voucher_date ?? '',

                        'opening_balance' => number_format($opening_balance, 2),

                        'amount' => optional($member->vouchers->first())->amount ?? '',
                        'instrument_amount' => $member->instrument_amount,
                        'this_year_balance' => $member->this_year_balance,
                    ];
                });
    
                //dd($members);
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
    //         $fromDate = $request->query('from_date');
    //         $toDate = $request->query('to_date');
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $query = TallyLedger::where('guid', 'like', $society->guid . '%')
    //             ->where('primary_group', 'Sundry Debtors')
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '');
    
    //         if ($fromDate && $toDate) {
    //             $query->whereHas('vouchers', function ($q) use ($fromDate, $toDate) {
    //                 $q->whereBetween('voucher_date', [$fromDate, $toDate]);
    //             });
    //         }
    
    //         $members = $query->with(['vouchers' => function ($query) use ($fromDate, $toDate) {
    //                 if ($fromDate && $toDate) {
    //                     $query->whereBetween('voucher_date', [$fromDate, $toDate]);
    //                 }
    //             }])
    //             ->orderBy('alias1', 'ASC')
    //             ->get()
    //             ->map(function ($member) {
    //                 // Calculate opening balance based on vouchers within the date range
    //                 $member->opening_balance = $member->vouchers->sum('amount'); // Adjust this based on your application logic
    
    //                 // Assuming `this_year_balance` is a property or method of $member
    //                 $member->this_year_balance = $member->this_year_balance; 
    
    //                 return [
    //                     'name' => $member->name,
    //                     'alias1' => $member->alias1,
    //                     'voucher_number' => optional($member->vouchers->first())->voucher_number ?? '',
    //                     'voucher_date' => optional($member->vouchers->first())->voucher_date ?? '',
    //                     'amount' => optional($member->vouchers->first())->amount ?? '',
    //                     'opening_balance' => $member->opening_balance,
    //                     'this_year_balance' => $member->this_year_balance,
    //                 ];
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
    //         $fromDate = $request->query('from_date');
    //         $toDate = $request->query('to_date');
    
    //         $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
    //         if (!$society) {
    //             return response()->json(['message' => 'Society not found'], 404);
    //         }
    
    //         $query = TallyLedger::where('guid', 'like', $society->guid . '%')
    //             ->where('primary_group', 'Sundry Debtors')
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '')
    //             ->orderBy('alias1', 'ASC');
    
    //             if ($fromDate && $toDate) {
    //                 $query->whereHas('vouchers', function ($q) use ($fromDate, $toDate) {
    //                     $q->whereBetween('voucher_date', [$fromDate, $toDate]);
    //                 });
    //             }
    
    //         $members = $query->withCount('vouchers')
    //             ->with('vouchers')
    //             ->latest()
    //             ->get()
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

}
