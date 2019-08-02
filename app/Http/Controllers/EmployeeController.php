<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Assignment;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        $jumlahKegiatanYangDiemban = [];
        foreach($users as $user){
            $assignment = DB::table('assignment')
                            ->join('activity', 'assignment.activity_id', '=', 'activity.id')
                            ->select(['awal', 'akhir'])
                            ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$user->id\"') = true")
                            ->whereDate('awal', '<=', now() )
                            ->whereDate('akhir', '>=', now() )
                            ->get();
            $count = $assignment->count();
            $jumlahKegiatanYangDiemban[$user->id] = $count;
        }
        return view('employee.index', ['users' => $users, 'jumlahKegiatanYangDiemban' => $jumlahKegiatanYangDiemban]);
    }
}
