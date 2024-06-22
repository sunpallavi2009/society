<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VoucherEntry;

class BillGenerationController extends Controller
{
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        $group = $request->query('group', 'Sundry Debtors'); // default to 'Sundry Debtors' if not provided
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        $voucherEntries = VoucherEntry::all();
        return view('superadmin.billGenerations.index', compact('society', 'societyGuid', 'group','voucherEntries'));
    }
}
