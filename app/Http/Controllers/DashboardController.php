<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $myAssignment = DB::table('assignment')
        ->join('activity', 'assignment.activity_id', '=', 'activity.id')
        ->join('sub_activity', 'assignment.sub_activity_id', '=' ,'sub_activity.id')
        ->select(['activity.name','sub_activity.name',
        'activity.awal as awal',
        'activity.akhir as akhir',        
        'petugas','realisasi',
        'assignment.sub_activity_id as sub_activity',
        'assignment.updated_at as update'
        ])
        ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
        ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
        ->orderBy('full_name', 'ASC')
        ->whereDate('awal', '<=', now() )
        ->whereDate('akhir', '>=', now() )
        ->get();
        // ->join('users', 'activity.created_by_user_id', '=', 'users.id')
        // dd($myAssignment);
        return view('dashboard', ['myAssignment' => $myAssignment]);
    }
}
