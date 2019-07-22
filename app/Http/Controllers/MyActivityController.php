<?php

namespace App\Http\Controllers;

use App\SubActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MyActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentYear = Carbon::now()->format('Y');
        $month = Input::get('month', 'now');
        $year = Input::get('year', $currentYear);
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
            ->whereJsonContains('petugas', Auth::id())
            ->orderBy('created_at', 'DESC');
        if ($month == 'now' && $year == $currentYear){
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', now() )
                                ->whereDate('akhir', '>=', now() )
                                ->paginate(10);
        }else if (in_array($month, config('scale.month')) && $year >= 2019 && $year <= $currentYear){
            $idMonth = (int)config('scale.month_reverse')[$month];
            $date = Carbon::parse("$year-$idMonth-2");
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date )
                                ->paginate(10);
        }else{
            return abort(404, 'bulan atau tahun yang akan dicari tidak valid');
        }
        return view('myactivity.index', ['sub_activity' => $sub_activity]);
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
     * @param  \App\SubActivity  $subActivity
     * @return \Illuminate\Http\Response
     */
    public function show(SubActivity $subActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubActivity  $subActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(SubActivity $subActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubActivity  $subActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubActivity $subActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubActivity  $subActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubActivity $subActivity)
    {
        //
    }
}
