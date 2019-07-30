<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;

class AssignmentController extends Controller
{

    function __construct()
    {
        $this->middleware(['web', 'auth']);
        $this->middleware('supervisor')->except(['show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monthQuery = Input::get('month', 'allMonth');
        $yearQuery = Input::get('year', now()->year);
        $showQuery = Input::get('show', 'showAll');
        $searchQuery = Input::get('query', '');
        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->join('assignment', 'assignment.sub_activity_id', '=', 'sub_activity.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'activity.created_by_user_id',
                'users.id as users_id',
                'users.name as user_name',
                'sub_activity.*',
                'assignment.id as assignment_id',
                'assignment.petugas as petugas'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->orderByDesc('created_at');
        if (!in_array($monthQuery, config('scale.month')) && $monthQuery != 'now' && $monthQuery != 'allMonth')
            return abort(404, 'Bulan yang akan dicari tidak valid, halaman tidak ditemukan');
        if ($yearQuery < 2019 || $yearQuery > now()->year)
            return abort(404, 'Tahun tidak valid, halaman tidak ditemukan');
        if (!in_array($showQuery, ['showAll', 'showAssignment', 'showUnassignment']))
            return abort(404, 'Filter tidak valid, halaman tidak ditemukan.');

        if ($monthQuery == 'now'){
            $sub_activity = $sub_activity->whereDate('awal','<=', Carbon::now()->day('2'))
                                        ->whereDate('akhir','>=', Carbon::now()->day('2'));
        }else if ($monthQuery == 'allMonth'){
            $sub_activity = $sub_activity->where(function($query) use($yearQuery){
                                $query->whereYear('awal', '=', $yearQuery)
                                    ->orWhereYear('akhir', '=', $yearQuery);
                            }, $boolean = 'and');
        }else{
            $monthNumber = config('scale.month_reverse')[$monthQuery];
            $x = Carbon::parse("$yearQuery-$monthNumber-2");
            $sub_activity = $sub_activity->whereDate('awal','<=', $x)
                                        ->whereDate('akhir','>=', $x);
        }

        if (!empty($searchQuery)){
            // Menampilkan sub dan kegiatan yang dicari berdasarkan namanya
            $sub_activity = $sub_activity->where(function($query) use($searchQuery){
                                $query->where('sub_activity.name','LIKE',"%$searchQuery%")
                                    ->where('activity.name','LIKE',"%$searchQuery%", 'OR');
                            }, $boolean = 'and');
        }
        
        if ($showQuery == 'showAssignment'){
            $sub_activity = $sub_activity
                                ->whereJsonLength('petugas', '!=', 0)
                                ->paginate(10);
            return view('assignment.index', ['sub_activity' => $sub_activity, 'show' => $showQuery]);
        }else if ($showQuery == 'showUnassignment'){
            $sub_activity = $sub_activity
                                ->whereJsonLength('petugas', '=', 0)
                                ->paginate(10);
            return view('assignment.index', ['sub_activity' => $sub_activity, 'show' => $showQuery]);
        }else{
            $sub_activity = $sub_activity->paginate(10);
            return view('assignment.index', ['sub_activity' => $sub_activity, 'show' => $showQuery]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function show(Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function edit(Assignment $assignment)
    {
        if($assignment->activity->created_by_user_id != auth()->user()->id && auth()->user()->role_id != 1)
            return abort(403, "Anda tidak diizinkan mengakases ini.");
        $users = User::all();
        $activity = $assignment->activity;
        $subActivity = $assignment->subActivity;
        $awal = Carbon::parse($activity->awal);
        $akhir = Carbon::parse($activity->akhir);
        $monthsConfig = config('scale.month');
        $months = [];
        $awalMonthId = (int)$awal->format('m');
        $akhirMonthId = (int)$akhir->format('m');
        if ($awal->format('Y') == $akhir->format('Y'))
            for($i = $awalMonthId-1; $i < $akhirMonthId; $i ++)
                array_push($months, ['monthName' => $monthsConfig[$i], 'monthId' => $i]);
        else{
            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($awal, $interval, $akhir);
            foreach($period as $dt){
                $mo = new Carbon($dt);
                $monthName = $mo->timezone('Asia/Jakarta')->formatLocalized('%B %Y');
                $monthId = $mo->timezone('Asia/Jakarta')->formatLocalized('%m');
                array_push($months, ['monthName' => $monthName, 'monthId' => $monthId]);
            }
        }
        $users_name = [];
        foreach($users as $user){
            $users_name[$user->id] = $user->name;
        }
        // dd($months);
        return view('assignment.add_edit_assignment', 
        [
            'users'=>$users,
            'users_name'=>$users_name,
            'assignment' => $assignment, 
            'activity' => $activity,
            'subActivity' => $subActivity,
            'months' => $months
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assignment $assignment)
    {
        $allReqeust = $request->all();
        $dataPetugasArray = [];
        foreach($allReqeust as $key=>$value){
            preg_match("/(\d+)_(.*)/", $key, $re);
            if (sizeof($re) != 0){
                if (!isset($dataPetugasArray[$re[1]]))
                    $dataPetugasArray[$re[1]] = [];
                $dataPetugasArray[(int)$re[1]][$re[2]] = (int)$value;
            }
        }
        Assignment::updateOrCreate(
            ['id' => $assignment->id], // Syarat
            [
                'petugas' => json_encode($dataPetugasArray)
            ], // update
        );
        return redirect()->route('assignment.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}
