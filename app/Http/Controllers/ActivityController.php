<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubActivity;
use Illuminate\Support\Facades\DB;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $showing = Input::get('showing', 'showAll');
        $userId = Auth::id();
        if ($showing == 'showAll'){
            $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'sub_activity.*'
            ])
            ->paginate(10);
            return view('activity.index', ['sub_activity' => $sub_activity, 'showing' => $showing]);
        }
        else if ($showing == 'showOnlyMe'){
            $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'sub_activity.*'
            ])
            ->where('users.id','=', $userId)
            ->paginate(10);
            return view('activity.index', ['sub_activity' => $sub_activity, 'showing' => $showing]);
        }
        else{
            $month = Input::get('month', Carbon::now()->formatLocalized('%B'));
            $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.id as users_id',
                'sub_activity.*'
            ])
            ->where('bulan_awal','=', $month)
            ->paginate(10);

            return view('activity.index', ['sub_activity' => $sub_activity, 'showing' => $showing]);
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
                'created_by_user_id' => auth()->user()->id,
                'bulan_awal' => $request['activity_start_month'],
                'tahun_awal' => $request['activity_end_year']
            ];
            if($request['issatubulan'] == false || $request['activity_start_month'] == $request['activity_end_month']){
                $data['bulan_akhir'] = $request['activity_end_month'];
                $data['tahun_akhir'] = $request['activity_end_year'];
            }

            $activity = Activity::create($data);
            if (sizeof(DB::table('autocomplete_activity')->where('name', $activity_name)->get()) == 0)
                DB::table('autocomplete_activity')->insert(['name' => $activity_name]);

            foreach($sub_activity as $key => $value){
                SubActivity::create([
                    'activity_id' => $activity->id,
                    'name' => $value['name'],
                    'satuan' => $value['satuan'],
                    'volume' => $value['volume'],

                    'pendidikan' => config('scale.pendidikan')[((int)$value['qualifikasi']['pendidikan']-1)],
                    'ti' => config('scale.likert')[((int)$value['qualifikasi']['ti']-1)],
                    'menulis' =>config('scale.likert')[((int)$value['qualifikasi']['menulis']-1)],
                    'administrasi' =>config('scale.likert')[((int)$value['qualifikasi']['administrasi']-1)],
                    'pengalaman_survei' => config('scale.likert')[((int)$value['qualifikasi']['pengalaman'] -1)],
                ]);
                if (sizeof(DB::table('autocomplete_sub_activity')->where('name', $value['name'])->get()) == 0)
                    DB::table('autocomplete_sub_activity')->insert(['name' => $value['name']]);
                if (sizeof(DB::table('autocomplete_satuan')->where('name', $value['satuan'])->get()) == 0)
                    DB::table('autocomplete_satuan')->insert(['name' => $value['satuan']]);
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
            ->where('sub_activity.id','=',$id)
            ->first();

            $users = DB::table('users')
            ->select([
                'users.*'
            ])
            ->get();
        if ($sub_activity != null){
            return view('activity.show', ['sub_activity' => $sub_activity],  ['users' => $users]);
        }else{
            return abort(404, "Kegiatan atau Sub-Kegiatan dengan id $id tidak ditemukan");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sub_activity = SubActivity::with('activity')->where('id','=',$id)->first();
        if ($sub_activity != null){
            return view('activity.edit', ['sub_activity' => $sub_activity]);
        }else{
            return abort(404, "Kegiatan atau Sub-Kegiatan yang akan diedit tidak ditemukan");
        }
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
        // dd($request->all());
        $SubActivityOriginal = SubActivity::find($id);
        $field = $request->all();
        $activity_name = $field['activity_name'];
        $activity_kategori = $field['activity_kategori'];
        $sub_activity = [];
        foreach($field as $key=>$value){
            preg_match("/sub_activity_(name|satuan|volume)/", $key, $re);
            if(sizeof($re) != 0) {
                $sub_activity[$re[1]] = $value;
            }
            preg_match("/q_sub_activity_(.*)/", $key, $qualifikasi);
            if(sizeof($qualifikasi) != 0){
                if(!isset($sub_activity['qualifikasi']))
                    $sub_activity['qualifikasi'] = [];
                $sub_activity['qualifikasi'][$qualifikasi[1]] = (int)$value;
            }
        }
        DB::transaction(function ()
        use(
            $SubActivityOriginal,
            $activity_name,
            $activity_kategori,
            $sub_activity,
            $request)
            {
            $data = [
                'name' => $activity_name,
                'kategori' => $activity_kategori,
                'bulan_awal' => $request['activity_start_month'],
                'tahun_awal' => $request['activity_end_year'],
                'bulan_akhir' => null,
                'tahun_akhir' => null,
            ];
            if($request['issatubulan'] == false ||
                ( $request['activity_start_month'] != $request['activity_end_month'] &&
                    $request['activity_start_year'] != $request['activity_end_year']) ){
                $data['bulan_akhir'] = $request['activity_end_month'];
                $data['tahun_akhir'] = $request['activity_end_year'];
            }

            $activity = Activity::where('id','=', $SubActivityOriginal->activity_id)->update($data);
            $SubActivityOriginal->update([
                'activity_id' => $SubActivityOriginal->activity_id,
                'name' => $sub_activity['name'],
                'satuan' => $sub_activity['satuan'],
                'volume' => $sub_activity['volume'],

                'pendidikan' => config('scale.pendidikan')[((int)$sub_activity['qualifikasi']['pendidikan']-1)],
                'ti' => config('scale.likert')[((int)$sub_activity['qualifikasi']['ti']-1)],
                'menulis' =>config('scale.likert')[((int)$sub_activity['qualifikasi']['menulis']-1)],
                'administrasi' =>config('scale.likert')[((int)$sub_activity['qualifikasi']['administrasi']-1)],
                'pengalaman_survei' => config('scale.likert')[((int)$sub_activity['qualifikasi']['pengalaman'] -1)],
            ]);
        });
        return redirect()->route('activity.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function() use($id) {
            $subactivity = SubActivity::find($id);
            if ($subactivity != null){
                $parentActivityId = $subactivity->activity_id;
                $statusDelete = $subactivity->delete();
                if ($statusDelete){
                    if (sizeof(DB::table('sub_activity')->where('activity_id',$parentActivityId)->get()) == 0 )
                        Activity::find($parentActivityId)->delete();
                    return response()->json(['status'=>'sukses', 'message'=>'Berhasil menghapus kegiatan'], 202);
                }else{
                    return response()->json(['status'=>'gagal', 'message'=>'Gagal meghapus kegiatan']);
                }
            }else{
                return response()->json(['status'=>'gagal', 'message'=>'ID tidak ditemukan'], 404);
            }
        });
    }

    public function autocomplete_activity(){
        $from_database = DB::table('autocomplete_activity')
                        ->select('name')
                        ->orderBy('name')
                        ->distinct()
                        ->get();
        return response()->json($from_database);
    }
    public function autocomplete_sub_activity(){
        $from_database = DB::table('autocomplete_sub_activity')
                        ->select('name')
                        ->orderBy('name')
                        ->distinct()
                        ->get();
        return response()->json($from_database);
    }
    public function autocomplete_satuan(){
        $from_database = DB::table('autocomplete_satuan')
                        ->select('name')
                        ->orderBy('name')
                        ->distinct()
                        ->get();
        return response()->json($from_database);
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


    public function showDetail($id)
    {
        $sub_activity = SubActivity::with('activity')->where('id','=',$id)->first();
        if ($sub_activity != null){
            return view('activity.detail', ['sub_activity' => $sub_activity]);
        }else{
            return abort(404, "Kegiatan atau Sub-Kegiatan tidak ditemukan");
        }
    }

}
