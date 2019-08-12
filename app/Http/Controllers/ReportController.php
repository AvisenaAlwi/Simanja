<?php

namespace App\Http\Controllers;


use App\SubActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use App\Assignment;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['supervisor'])->except('print_ckp');
    }

    public function index()
    {
        $searchQuery = Input::get('query', '');
        $userId = Auth::id();
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->formatLocalized('%B');
        $month = Input::get('month', 'now');
        $year = Input::get('year', $currentYear);
        if($month == 'now')
            $month = $currentMonth;
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
                'assignment.petugas as petugas',
                'assignment.realisasi as realisasi',
                'assignment.keterangan as keterangan',
                'nip'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->where('user_id','=',auth()->user()->id)
            ->orderBy('created_at', 'DESC');
        if (!empty($searchQuery)){
            // Menampilkan sub dan kegiatan yang dicari berdasarkan namanya
            $sub_activity = $sub_activity
                                ->where('sub_activity.name','LIKE',"%$searchQuery%")
                                ->where('activity.name','LIKE',"%$searchQuery%", 'OR');
        }
        if ($month == 'now' && $year == $currentYear){
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', now() )
                                ->whereDate('akhir', '>=', now() );
        }else if (in_array($month, config('scale.month')) && $year >= 2019 && $year <= $currentYear){
            $idMonth = (int)config('scale.month_reverse')[$month];
            $date = Carbon::parse("$year-$idMonth-2");
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date );
        }else{
            return abort(404, 'bulan atau tahun yang akan dicari tidak valid');
        }
        $sub_activity = $sub_activity->paginate(10);
        return view('report.index', ['sub_activity' => $sub_activity]);
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

    function pelaporan (Subactivity $id){

        // dd($id);
        $activity = $id->activity;
        $awal = Carbon::parse($activity->awal);
        $akhir = Carbon::parse($activity->akhir);
        $months = [];

        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($awal, $interval, $akhir);
        foreach($period as $dt){
            $mo = new Carbon($dt);
            $monthName = $mo->timezone('Asia/Jakarta')->formatLocalized('%B %Y');
            $monthId = $mo->timezone('Asia/Jakarta')->formatLocalized('%m');
            array_push($months, ['monthName' => $monthName, 'monthId' => $monthId]);
        }
        // dd($months);

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
                'assignment.petugas as petugas',
                'assignment.keterangan as keterangan_r',
                'assignment.realisasi as realisasi',
                'assignment.tingkat_kualitas as tingkat_kualitas'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->where('sub_activity.id','=',$id->id)
            ->first();
            // dd($sub_activity);

            $users = DB::table('users')
            ->select([
                'users.*'
            ])
            ->get();

        if ($sub_activity != null){
            // dd($period);
            return view('report.show_pelaporan', ['idz'=>$id->id,'sub_activity' => $sub_activity], ['period'=>$months]);
        }else{
            return abort(404, "Kegiatan atau Sub-Kegiatan dengan id $id tidak ditemukan");
        }
    }

    function update_pelaporan (Request $request, $assignmentId){
        $f = Assignment::where('sub_activity_id','=',$assignmentId)->first();
        // dd($f->toJson());
        $realisasi = json_decode($f->realisasi, true);
        $keterangan = json_decode($f->keterangan, true);
        $tingkul = json_decode($f->tingkat_kualitas, true);
        $userID = json_decode($request->userArray, true);
        $realisasiR = json_decode($request->realisasi, true);
        $keteranganR = json_decode($request->keterangan, true);
        $tingkulR = json_decode($request->kualitas, true);
        foreach ($userID as $idx => $idUser) {
            $realisasi[$idUser][$request->monthYear] = (int) $realisasiR[$idx];
            $tingkul[$idUser][$request->monthYear] = (int) $tingkulR[$idx];
            $keterangan[$idUser][$request->monthYear] = $keteranganR[$idx];
        }
        $f->update([
            'realisasi' => json_encode($realisasi),
            'keterangan' => json_encode($keterangan),
            'tingkat_kualitas' => json_encode($tingkul),
            'update_state' => 0,
            'init_assign' => empty(json_decode($f->petugas, true)) ? true : false,
        ]);
    }

}
