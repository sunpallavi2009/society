<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Member;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{

    public function memberIndex(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.members.index', compact('society', 'societyGuid', 'group'));
    }
    public function membergetData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            $group = $request->query('group');
    
            $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
            if (!$society) {
                return response()->json(['message' => 'Society not found'], 404);
            }
    
            $query = TallyLedger::where('guid', 'like', $society->guid . '%');
    
            if ($group == 'Sundry Debtors') {
                $query->where('primary_group', 'Sundry Debtors')
                      ->whereNotNull('alias1')
                      ->where('alias1', '!=', '');
            } else {
                $query->where('primary_group', '!=', 'Sundry Debtors');
            }
    
            $members = $query->withCount('vouchers')
                ->with('vouchers')
                ->latest()
                ->get()
                ->map(function($member) {
                    $member->first_voucher_date = $member->firstVoucherDate();
                    return $member;
                });


                // dd($members);
    
            return DataTables::of($members)
                ->addIndexColumn()
                ->make(true);
        }
    }
    

    public function memberOutstandingIndex(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.memberOutstanding.index', compact('society', 'societyGuid', 'group'));
    }

    public function memberOutstandingGetData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            $group = $request->query('group');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');
    
            $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    
            if (!$society) {
                return response()->json(['message' => 'Society not found'], 404);
            }
    
            $query = TallyLedger::where('guid', 'like', $society->guid . '%')
                ->whereNotNull('alias1')
                ->where('alias1', '!=', '');
    
                if ($fromDate && $toDate) {
                    $query->whereHas('vouchers', function ($q) use ($fromDate, $toDate) {
                        $q->whereBetween('voucher_date', [$fromDate, $toDate]);
                    });
                }
    
            $members = $query->withCount('vouchers')
                ->with('vouchers')
                ->latest()
                ->get()
                ->map(function ($member) {
                    // Assuming first_voucher_date is a dynamic property/method
                    $member->first_voucher_date = $member->firstVoucherDate();
                    $member->voucher_number = $member->vouchers->first()->voucher_number ?? '';
                    $member->amount = $member->vouchers->first()->amount ?? '';
                    return $member;
                });
    
            return DataTables::of($members)
                ->addIndexColumn()
                ->make(true);
        }
    }


}
