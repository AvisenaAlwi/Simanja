<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;

class ReportController extends Controller
{
    //

    public function index()
    {
        $searchQuery = Input::get('query', '');
        $userId = Auth::id();
        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'sub_activity.*',
                'activity.awal',
                'activity.akhir',
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->orderBy('created_at', 'DESC');
        if (!empty($searchQuery)){
            // Menampilkan sub dan kegiatan yang dicari berdasarkan namanya
            $sub_activity = $sub_activity
                                ->where('sub_activity.name','LIKE',"%$searchQuery%")
                                ->where('activity.name','LIKE',"%$searchQuery%", 'OR');
        }
        $sub_activity = $sub_activity->where('users.id','=', $userId)
                                ->paginate(10);

        return view('report.index', ['sub_activity' => $sub_activity]);
    }

    public function print_ckp()
    {
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->formatLocalized('%B');
        $ckp = Input::get('ckp','x');
        $month = Input::get('month', 'now');
        $year = Input::get('year', $currentYear);
        $penilai = DB::table('users')
        ->select(['users.*'])
        ->where('nip','=',auth()->user()->pejabat_penilai_nip)
        ->first();

        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'sub_activity.*',
                'activity.awal',
                'activity.akhir',
                'activity.kategori as kategori'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->whereJsonContains('petugas', Auth::id())
            ->orderBy('created_at', 'DESC');
        if ($month == 'now' && $year == $currentYear){
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', now() )
                                ->whereDate('akhir', '>=', now() )
                                ->get();
        }else if (in_array($month, config('scale.bulan')) && $year >= 2019 && $year <= $currentYear){
            $idMonth = (int)config('scale.bulan_reverse')[$month];
            $date = Carbon::parse("$year-$idMonth-2");
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date )
                                ->get();
        }else{
            return abort(404, 'bulan atau tahun yang akan dicari tidak valid');
        }
        $utama = [];
        $tambahan = [];

        foreach($sub_activity as $sub){
            if ($sub->kategori == 'Utama'){
                array_push($utama, $sub);
            }else{
                array_push($tambahan, $sub);
            }
        }
        if ($month=='now') {
            $month = $currentMonth;
        }
        if($ckp == 't'){
            // $pdf = PDF::loadview('report.ckpt',
            // ['penilai' => $penilai, 'sub_activity' => $sub_activity, 'keg_utama' =>  $utama, 'keg_tambahan' =>  $tambahan]);
    	    // return $pdf->stream($month.'_'.$year.'_CKP-T_'.auth()->user()->name.'_'.auth()->user()->nip.'.pdf');
            return view('report.ckpt',
            ['penilai' => $penilai, 'sub_activity' => $sub_activity, 'keg_utama' =>  $utama, 'keg_tambahan' =>  $tambahan]);

        }else if($ckp == 'r'){
            $pdf = PDF::loadview('report.ckpr',
            ['penilai' => $penilai, 'sub_activity' => $sub_activity, 'keg_utama' =>  $utama, 'keg_tambahan' =>  $tambahan]);
    	    return $pdf->stream($month.'_'.$year.'_CKP-R_'.auth()->user()->name.'_'.auth()->user()->nip.'.pdf');
            // return view('report.ckpr',
            // ['penilai' => $penilai, 'sub_activity' => $sub_activity, 'keg_utama' =>  $utama, 'keg_tambahan' =>  $tambahan]);
        }else{
            return abort(404, 'CKP tidak valid');
        }

    }

    function pelaporan ($id){
        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.id as users_id',
                'sub_activity.*',
                'sub_activity.id as sub_activity_id',
                'activity.*',
                'users.name as user_name'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->where('sub_activity.id','=',$id)
            ->first();

            $users = DB::table('users')
            ->select([
                'users.*'
            ])
            ->get();
        if ($sub_activity != null){
            return view('report.show_pelaporan', ['sub_activity' => $sub_activity],  ['users' => $users]);
        }else{
            return abort(404, "Kegiatan atau Sub-Kegiatan dengan id $id tidak ditemukan");
        }
    }

    function update_pelaporan ($id){
}
}
