<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['supervisor'])->except('print_ckp');
    }

    public function index()
    {
        $showing = Input::get('showing', 'showCreate');
        $searchQuery = Input::get('query', '');
        $userId = Auth::id();
        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->join('assignment', 'assignment.sub_activity_id', '=', 'sub_activity.id')
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
            ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
            ->whereDate('awal', '<=', now() )
            ->whereDate('akhir', '>=', now() )
            ->orderBy('created_at', 'DESC');
        if (!empty($searchQuery)){
            // Menampilkan sub dan kegiatan yang dicari berdasarkan namanya
            $sub_activity = $sub_activity
                                ->where('sub_activity.name','LIKE',"%$searchQuery%")
                                ->where('activity.name','LIKE',"%$searchQuery%", 'OR');
        }
        if ($showing=='showCreate') {
            $sub_activity = $sub_activity->where('users.id','=', $userId);
        }
        else if ($showing=='showMe') {
            $sub_activity = $sub_activity->whereJsonContains('petugas',Auth()->user()->id);
        }
        else
        {
            return abort(404, 'Autentikasi error');
        }
        $sub_activity = $sub_activity->paginate(10);
        return view('report.index', ['sub_activity' => $sub_activity, 'showing' => $showing]);
    }

    public function print_ckp()
    {
        $userId = Auth::id();
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
            ->join('assignment', 'assignment.sub_activity_id', '=', 'sub_activity.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'sub_activity.*',
                'activity.awal',
                'activity.akhir',
                'activity.kategori as kategori',
                'assignment.petugas as petugas',
                'assignment.keterangan as keterangan_r',
                'assignment.realisasi as realisasi',
                'assignment.tingkat_kualitas as tingkat_kualitas'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
            ->orderBy('created_at', 'DESC');
        $my_activity = DB::table('my_activity')
                        ->select([
                            'name as full_name',
                            'my_activity.*'
                        ])
                        ->where('created_by_user_id', '=', Auth::id())
                        ->orderBy('created_at', 'DESC');
        if ($month == 'now' && $year == $currentYear){
            $date = now();
            $idMonth = (int)$date->format('m');
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date )
                                ->get();
            $my_activity = $my_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date )
                                ->get();
        }else if (in_array($month, config('scale.month')) && $year >= 2019 && $year <= $currentYear){
            $idMonth = (int)config('scale.month_reverse')[$month];
            $date = Carbon::parse("$year-$idMonth-2");
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date )
                                ->get();
            $my_activity = $my_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date )
                                ->get();
        }else{
            return abort(404, 'bulan atau tahun yang akan dicari tidak valid');
        }

        if ($month=='now') {
            $month = $currentMonth;
        }

        $utama = [];
        $tambahan = [];
        foreach($sub_activity as $sub){
            try{
                $sub->month_volume = json_decode($sub->petugas, true)[$userId]["${month}_${currentYear}"];
            }catch(Exception $e){
                $sub->month_volume = json_decode($sub->petugas, true)[$userId]["${month}"];
            }
            if ($sub->month_volume != 0 && $sub->month_volume > 0){
                if ($sub->kategori == 'Utama'){
                    $sub->tipe = 'tugas';
                    array_push($utama, $sub);
                }else{
                    $sub->tipe = 'tugas';
                    array_push($tambahan, $sub);
                }
            }
        }
        // die();
        foreach($my_activity as $my){
            $my->month_volume = $my->volume;
            if ($my->month_volume != 0 && $my->month_volume > 0){
                if ($my->kategori == 'Utama'){
                    $my->tipe = 'myactivity';
                    array_push($utama, $my);
                }else{
                    $my->tipe = 'myactivity';
                    array_push($tambahan, $my);
                }
            }
        }
        if($ckp == 't'){
            $pdf = PDF::loadview('report.ckpt',
            ['penilai' => $penilai, 'sub_activity' => $sub_activity, 'keg_utama' =>  $utama, 'keg_tambahan' =>  $tambahan, 'date'=>$date]);
            return $pdf->stream($month.'_'.$year.'_CKP-T_'.auth()->user()->name.'_'.auth()->user()->nip.'.pdf');
            // return view('report.ckpt',
            // ['penilai' => $penilai, 'sub_activity' => $sub_activity, 'keg_utama' =>  $utama, 'keg_tambahan' =>  $tambahan]);

        }else if($ckp == 'r'){
            $pdf = PDF::loadview('report.ckpr',
            ['penilai' => $penilai, 'sub_activity' => $sub_activity, 'keg_utama' =>  $utama, 'keg_tambahan' =>  $tambahan, 'date'=>$date]);
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
            ->join('assignment', 'assignment.sub_activity_id', '=', 'sub_activity.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.id as users_id',
                'sub_activity.*',
                'sub_activity.id as sub_activity_id',
                'activity.*',
                'users.name as user_name',
                'assignment.petugas'
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
