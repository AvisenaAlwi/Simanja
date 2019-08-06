<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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
        $searchQuery = Input::get('query', '');
        $user = DB::table('users')
        ->select(['users.*']);
        if (!empty($searchQuery)){
            $user = $user
            ->where('users.name','LIKE',"%$searchQuery%")
            ->where('users.nip','LIKE',"%$searchQuery%", 'OR');
        }
        $user = $user->paginate(10);
        // dd($jumlahKegiatanYangDiemban);
        return view('employee.index', ['users' => $user, 'jumlahKegiatanYangDiemban' => $jumlahKegiatanYangDiemban]);
    }
}
