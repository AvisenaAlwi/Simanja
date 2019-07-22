<?php

namespace App\Http\Controllers;

use App\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
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
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.id as users_id',
                'sub_activity.*'
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

        return view('assignment.add_edit_assignment', ['assignment' => $assignment]);
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
        //
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
