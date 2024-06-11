<?php

namespace App\Http\Controllers\SuperAdmin;


use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SocietyController extends Controller
{
    public function index()
    {
        return view ('superadmin.society.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societies = TallyCompany::query();

            // Handle search
            if ($request->has('search.value')) {
                $searchValue = $request->input('search.value');
                $societies->where(function ($query) use ($searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('address1', 'like', '%' . $searchValue . '%')
                        ->orWhere('mobile_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('website', 'like', '%' . $searchValue . '%')
                        ->orWhere('company_number', 'like', '%' . $searchValue . '%');
                });
            }

            // Proceed with pagination and ordering
            return DataTables::of($societies)
                ->addIndexColumn()
                ->make(true);
        }
    }


    // public function getData(Request $request)
    // {
    //     // $societies = TallyCompany::latest()->get();
    //     // dd($societies);
    //     if ($request->ajax()) {
    //         $societies = TallyCompany::latest()->get();
    //         if($societies->isEmpty()) {
    //             return response()->json(['error' => 'No data found'], 404);
    //         }
    //         // dd($societies);
    //         return DataTables::of($societies)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }

    public function societyDashboard(Request $request)
    {
        $societyGuid = $request->query('guid');
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
        // You might want to handle the case when society is not found
        return view('superadmin.dashboard', compact('society', 'societyGuid'));
    }
}
