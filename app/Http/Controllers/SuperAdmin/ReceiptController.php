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


    public function getData(Request $request)
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

        $query = TallyLedger::where('guid', 'like', $society->guid . '%');

        if ($group == 'Sundry Debtors') {
            $query->where('primary_group', 'Sundry Debtors')
                  ->whereNotNull('alias1')
                  ->where('alias1', '!=', '');
        } else {
            $query->where('primary_group', '!=', 'Sundry Debtors');
        }

        // Apply date range filter
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('voucher_date', [$fromDate, $toDate]);
        }

        $members = $query->withCount('vouchers')
            ->with('vouchers')
            ->latest()
            ->get()
            ->map(function($member) {
                $member->voucher_date = $member->voucher_date();
                return $member;
            });

        return DataTables::of($members)
            ->addIndexColumn()
            ->make(true);
    }
}


}
