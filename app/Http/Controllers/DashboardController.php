<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\User;
use Carbon\Carbon;
use Hamcrest\Core\Set;
use App\Assignment;
use App\SubActivity;
use App\MyActivity;
use DateInterval;
use DatePeriod;
use stdClass;

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
        $monthYearNow = Carbon::now()->timezone('Asia/Jakarta')->formatLocalized('%B_%Y');
        $userId = auth()->user()->id;
        $myAssignment = DB::table('assignment')
        ->join('activity', 'assignment.activity_id', '=', 'activity.id')
        ->join('sub_activity', 'assignment.sub_activity_id', '=' ,'sub_activity.id')
        ->select(['activity.name','sub_activity.name',
        'activity.awal as awal',
        'activity.akhir as akhir',
        'petugas','realisasi',
        'assignment.sub_activity_id as sub_activity',
        'assignment.updated_at as update',
        'assignment.sub_activity_id'
        ])
        ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
        ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
        ->orderBy('full_name', 'ASC')
        ->whereDate('awal', '<=', now() )
        ->whereDate('akhir', '>=', now() )
        ->get();
        // Card 1
        $usersAll = User::all();
        $usersCount = $usersAll->count();
        $usersQuery = User::whereDate('created_at', '<', Carbon::parse('first day of January'));
        $usersQueryCount = $usersQuery->count();
        $usersPercent = ($usersCount - $usersQueryCount) * $usersQueryCount / 100;
        // Card 2
        $userSetDitugaskan = [];
        $assignmentsThisMonth = DB::table('assignment')
        ->join('activity', 'assignment.activity_id', '=', 'activity.id')
        ->whereDate('activity.awal', '<=', now())
        ->whereDate('activity.akhir', '>=', now())
        ->select(['petugas', 'realisasi'])
        ->get();
        foreach($assignmentsThisMonth as $val){
            $userSetDitugaskan = $userSetDitugaskan + json_decode($val->petugas, true);
        }
        // Card 3
        // $totalSubActivity = 0;
        $x = DB::table('sub_activity')->join('activity', 'activity_id', '=', 'activity.id')->whereDate('awal', '<=', now())->whereDate('akhir', '>=', now())->get()->count();
        $y = DB::table('my_activity')->whereDate('awal', '<=', now())->whereDate('akhir', '>=', now())->get()->count();
        $totalSubActivity = $x + $y;
        // Card 4
        $alokasi = 0;
        $realisasi = 0;
        foreach($assignmentsThisMonth as $row){
            foreach(json_decode($row->petugas, true) as $user){
                $alokasi += isset($user[$monthYearNow]) ? $user[$monthYearNow] : 0;
            }
            foreach(json_decode($row->realisasi, true) as $realisasiUser){
                $realisasi += isset($realisasiUser[$monthYearNow]) ? $realisasiUser[$monthYearNow] : 0;
            }
        }
        foreach(MyActivity::whereDate('awal', '<=', now())->whereDate('akhir', '>=', now())->get() as $row){
            $alokasi += $row->volume;
            $realisasi += $row->realisasi;
        }

        $months = [];

        // Realisasi 6 bulan terakhir
        $awal = Carbon::parse('first day of this month')->subMonths(6);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($awal, $interval, Carbon::now()->subMonth());
        $realisasiMonthValue = [];
        $activityCountMonthValue = [];
        foreach($period as $month){
            $assignmentsMonth = DB::table('assignment')
                ->join('activity', 'assignment.activity_id', '=', 'activity.id')
                ->whereDate('activity.awal', '<=', $month)
                ->whereDate('activity.akhir', '>=', $month)
                ->select(['petugas', 'realisasi'])
                ->get();
            // echo $month->formatLocalized('%B_%Y');
            // echo '<br>';
            // print_r($assignmentsMonth);
            // echo '<br>';
            // echo '<br>';
            $ac = DB::table('sub_activity')->join('activity', 'activity.id', '=', 'sub_activity.activity_id')
                    ->whereDate('activity.awal', '<=', $month)
                    ->whereDate('activity.akhir', '>=', $month)
                    ->get();
            $alokasiMonth = 0;
            $realisasiMonth = 0;
            foreach($assignmentsMonth as $row){
                foreach(json_decode($row->petugas, true) as $user){
                    $alokasiMonth += isset($user[$month->formatLocalized('%B_%Y')]) ? $user[$month->formatLocalized('%B_%Y')] : 0;
                }
                foreach(json_decode($row->realisasi, true) as $realisasiUser){
                    $realisasiMonth += isset($realisasiUser[$month->formatLocalized('%B_%Y')]) ? $realisasiUser[$month->formatLocalized('%B_%Y')] : 0;
                }
            }
            foreach(MyActivity::whereDate('awal', '<=', $month)->whereDate('akhir', '>=', $month)->get() as $row){
                $alokasiMonth += $row->volume;
                $realisasiMonth += $row->realisasi;
            }
            array_push($months, $month->timezone('Asia/Jakarta')->formatLocalized('%b'));
            array_push($realisasiMonthValue, $alokasiMonth == 0 ? 0 : (int)($realisasiMonth/$alokasiMonth*100));
            array_push($activityCountMonthValue, $ac->count());
        }
        return view('dashboard', [
            'myAssignment' => $myAssignment,
            'activeUsers' => $usersAll->count(),
            'usersPercent' => $usersPercent,
            'userDitugaskan' => sizeof($userSetDitugaskan),
            'totalSubActivity' => $totalSubActivity,
            'alokasiBulanSekarang' => $alokasi,
            'realisasiBulanSekarang' => $realisasi,
            'monthLabel' => $months,
            'monthRealisasiValue' => $realisasiMonthValue,
            'activityCountMonthValue' => $activityCountMonthValue,
        ]);
    }
}
