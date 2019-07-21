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
        $showMonth = Input::get('showMonth', 'showAllMonth');
        $show = Input::get('show', 'showAll');
        $userId = Auth::id();
        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.id as users_id',
                'sub_activity.*'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name");
        if ($showMonth == 'showCurrentMonth'){
            $month = Input::get('month', Carbon::now()->formatLocalized('%B'));
            $sub_activity = $sub_activity->whereDate('awal','<=', Carbon::now()->day('2'))
                                        ->whereDate('akhir','>=', Carbon::now()->day('2'));
        }

        if ($show == 'showAssignment'){
            $sub_activity = $sub_activity
                                ->where('petugas','!=', '[]')
                                ->paginate(10);
            return view('assignment.index', ['sub_activity' => $sub_activity, 'show' => $show, 'showMonth' => $showMonth]);
        }else if ($show == 'showUnassignment'){
            $sub_activity = $sub_activity
                                ->where('petugas','=', '[]')
                                ->paginate(10);
            return view('assignment.index', ['sub_activity' => $sub_activity, 'show' => $show, 'showMonth' => $showMonth]);
        }else{
            $sub_activity = $sub_activity->paginate(10);
            return view('assignment.index', ['sub_activity' => $sub_activity, 'show' => $show, 'showMonth' => $showMonth]);
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
