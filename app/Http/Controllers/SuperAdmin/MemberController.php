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

    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('superadmin.members.index', compact('society', 'societyGuid', 'group'));
    }


    public function getData(Request $request)
    {
        if ($request->ajax()) {

            // \Log::info($request->all());

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

                // \Log::info($members->all());
                // dd($members);
    
            return DataTables::of($members)
                ->addIndexColumn()
                ->make(true);
        }
    }


    public function assignRole(Request $request)
    {
        if ($request->ajax()) {
            $guid = $request->input('guid');
            $role = $request->input('role');

            // Update the assign_admin column in the database
            $ledger = TallyLedger::where('guid', $guid)->first();
            if ($ledger) {
                $ledger->assign_admin = $role;
                $ledger->save();

                return response()->json(['message' => 'Role assigned successfully']);
            }

            return response()->json(['error' => 'Ledger not found'], 404);
        }

        return response()->json(['error' => 'Method not allowed'], 405);
    }

}
