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
        $month = Input::get('month', 'now');
        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'sub_activity.*',
                'activity.tahun_awal',
                'activity.tahun_akhir',
            ])
            ->whereJsonContains('petugas', Auth::id())
            ->orderBy('created_at', 'DESC');
        if ($month == 'now'){
            $currentMonth = config('scale.bulan')[(int)Carbon::now()->format('m') - 1];
            $sub_activity = $sub_activity
                                ->whereRaw('Is_In_Month_Range(?, activity.bulan_awal, activity.tahun_awal, activity.bulan_akhir, activity.tahun_akhir)', [$currentMonth])
                                ->paginate(10);
        }else if (in_array($month, config('scale.bulan'))){
            $sub_activity = $sub_activity
                                ->whereRaw('Is_In_Month_Range(?, activity.bulan_awal, activity.tahun_awal, activity.bulan_akhir, activity.tahun_akhir)', [$month])
                                ->paginate(10);
        }else{
            return abort(404, 'bulan yang akan dicari tidak ditemukan');
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
