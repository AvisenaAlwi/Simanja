<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubActivity;
use Illuminate\Support\Facades\DB;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $showAll = Input::get('all', 'false');
        if ($showAll == 'true'){
            $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'sub_activity.*',
            ])
            ->paginate(10);
            return view('activity.index', ['sub_activity' => $sub_activity]);
        }else{
            $month = Input::get('month', Carbon::now()->formatLocalized('%B'));
            $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'sub_activity.*',
            ])
            ->where('awal_bulan','=', $month)
            ->paginate(10);
            return view('activity.index', ['sub_activity' => $sub_activity]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activity.add_activity');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'activity_name' => 'required',
            'activity_kategori' => 'required'
        ]);
        $field = $request->all();
        $activity_name = $field['activity_name'];
        $activity_kategori = $field['activity_kategori'];
        $sub_activity = [];
        foreach($field as $key=>$value){
            preg_match("/sub_activity_(\d+)_(name|satuan|volume)/", $key, $re);
            if(sizeof($re) != 0) {
                $sub_activity[$re[1]][$re[2]] = $value;
            }
            preg_match("/q_sub_activity_(\d+)_(.*)/", $key, $qualifikasi);
            if(sizeof($qualifikasi) != 0){
                if(!isset($sub_activity[$qualifikasi[1]]['qualifikasi']))
                    $sub_activity[$qualifikasi[1]]['qualifikasi'] = [];
                $sub_activity[$qualifikasi[1]]['qualifikasi'][$qualifikasi[2]] = (int)$value;
            }
        }
        DB::transaction(function () 
        use(
            $activity_name, 
            $activity_kategori, 
            $sub_activity,
            $request)
            {
            $data = [
                'name' => $activity_name, 
                'kategori' => $activity_kategori, 
                'awal_bulan' => $request['activity_start_month'],
                'tahun' => now()->format('Y')
            ];
            if($request['issatubulan'] == false || $request['activity_start_month'] == $request['activity_end_month'])
                $data['akhir_bulan'] = $request['activity_end_month'];

            $activity = Activity::create($data);
            foreach($sub_activity as $key => $value){
                SubActivity::create([
                    'activity_id' => $activity->id,
                    'satuan' => $value['satuan'], 
                    'name' => $value['name'],
                    'volume' => $value['volume'],
                    'tahun' => now()->format('Y'),

                    'pendidikan' =>$value['qualifikasi']['pendidikan'],
                    'ti' =>$value['qualifikasi']['ti'],
                    'menulis' =>$value['qualifikasi']['menulis'],
                    'administrasi' =>$value['qualifikasi']['administrasi'],
                    'pengalaman_survei' =>$value['qualifikasi']['pengalaman'],
                ]);
            }
        });
        // echo $activity_name."<br>";
        // echo $activity_kategori."<br>";
        // echo $activity_volume."<br>";
        // print_r($sub_activity);
        return redirect()->route('activity.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sub_activity = Activity::find($id);
        return view('activity.show', ['data' => $sub_activity]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function anyData()
    {
        // echo "Sena";
        // die();
        return Datatables::of(
            DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'sub_activity.*',
            ])
        )->make(true);
    }
}
