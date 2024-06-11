<?php

namespace App\Http\Controllers\SuperAdmin;

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
            $billDate = $request->query('bill_date'); // Retrieve bill_date parameter
    
            $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
            if (!$society) {
                return response()->json(['message' => 'Society not found'], 404);
            }
    
            $query = TallyLedger::where('guid', 'like', $society->guid . '%')
                ->whereNotNull('alias1')
                ->where('alias1', '!=', '');
    
            // Filter data based on bill_date
            if ($billDate) {
                // Assuming voucher_date is the column in vouchers table to filter
                $query->whereHas('vouchers', function ($q) use ($billDate) {
                    $q->whereDate('voucher_date', '=', $billDate);
                });
            }
    
            $members = [];
            $query->chunk(100, function ($members) use (&$results) {
                foreach ($members as $member) {
                    $member->first_voucher_date = $member->firstVoucherDate();
                    $member->voucher_number = $member->vouchers->first()->voucher_number ?? '';
                    $member->amount = $member->vouchers->first()->amount ?? '';
                    $results[] = $member;
                }
            });
    
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
