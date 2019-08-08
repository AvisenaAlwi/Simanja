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
use App\Assignment;

class ActivityController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['supervisor'])->except('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $showing = Input::get('showing', 'showCurrentMonth');
        $searchQuery = Input::get('query', '');
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->formatLocalized('%B');
        $month = Input::get('month', 'now');
        $year = Input::get('year', $currentYear);
        if($month == 'now')
            $month = $currentMonth;

        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'users.photo',
                'sub_activity.*',
                'activity.awal',
                'activity.akhir',
                'activity.created_by_user_id',
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->orderBy('created_at', 'DESC');

            if ($month == 'now' && $year == $currentYear){
                $sub_activity = $sub_activity
                                    ->whereDate('awal', '<=', now() )
                                    ->whereDate('akhir', '>=', now() );
            }else if (in_array($month, config('scale.month')) && $year >= 2019 && $year <= $currentYear)
            {
                $idMonth = (int)config('scale.month_reverse')[$month];
                $date = Carbon::parse("$year-$idMonth-2");
                $sub_activity = $sub_activity
                                    ->whereDate('awal', '<=', $date )
                                    ->whereDate('akhir', '>=', $date );
            }
            else{
                return abort(404, 'bulan atau tahun yang akan dicari tidak valid');
            }


        if (!empty($searchQuery)){
            // Menampilkan sub dan kegiatan yang dicari berdasarkan namanya
            $sub_activity = $sub_activity->where(function($query) use($searchQuery){
                                $query->where('sub_activity.name','LIKE',"%$searchQuery%")
                                    ->where('activity.name','LIKE',"%$searchQuery%", 'OR');
                            }, $boolean = 'and');
        }

            $sub_activity = $sub_activity->paginate(10);
        return view('activity.index', ['sub_activity' => $sub_activity, 'showing' => $showing]);
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
        $validatedData = $request->validate([
            'activity_name' => 'required',
            'activity_kategori' => 'required'
        ]);
        $field = $request->all();
        $activity_name = $field['activity_name'];
        $activity_kategori = $field['activity_kategori'];
        $sub_activity = [];
        foreach($field as $key=>$value){
            preg_match("/sub_activity_(\d+)_(name|satuan|volume|kode_butir|angka_kredit|keterangan)/", $key, $re);
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
                'created_by_user_id' => auth()->user()->id
            ];
            $startMonth = config('scale.month_reverse')[$request['activity_start_month']];
            $startYear = (int)$request['activity_start_year'];
            $awal = Carbon::parse("$startYear-$startMonth-01")->startOfMonth();
            $endMonth = config('scale.month_reverse')[$request['activity_end_month']];
            $endYear = (int)$request['activity_end_year'];
            $akhir = Carbon::parse("$startYear-$startMonth-28")->endOfMonth();

            $data['awal'] = $awal;
            if($request['issatubulan'] == false)
                $akhir = Carbon::parse("$endYear-$endMonth-28")->endOfMonth();
            $data['akhir'] = $akhir;

            $activity = Activity::create($data);
            if (sizeof(DB::table('autocomplete_activity')->where('name', $activity_name)->get()) == 0)
                DB::table('autocomplete_activity')->insert(['name' => $activity_name]);

            foreach($sub_activity as $key => $value){
                $sub = SubActivity::create([
                    'activity_id' => $activity->id,
                    'name' => $value['name'],
                    'satuan' => $value['satuan'],
                    'volume' => $value['volume'],
                    'kode_butir' => !empty($value['kode_butir']) ? $value['kode_butir'] : null,
                    'angka_kredit' => !empty($value['angka_kredit']) ? $value['angka_kredit'] : null,
                    'keterangan' => !empty($value['keterangan']) ? $value['keterangan'] : null,

                    'pendidikan' => config('scale.pendidikan')[((int)$value['qualifikasi']['pendidikan']-1)],
                    'ti' => config('scale.likert')[((int)$value['qualifikasi']['ti']-1)],
                    'menulis' =>config('scale.likert')[((int)$value['qualifikasi']['menulis']-1)],
                    'administrasi' =>config('scale.likert')[((int)$value['qualifikasi']['administrasi']-1)],
                    'pengalaman_survei' => config('scale.likert')[((int)$value['qualifikasi']['pengalaman'] -1)],
                ]);
                Assignment::create([
                    'activity_id' => $activity->id,
                    'sub_activity_id' => $sub->id,
                    'user_id' => Auth::id()
                ]);
                if (sizeof(DB::table('autocomplete_sub_activity')->where('name', $value['name'])->get()) == 0)
                    DB::table('autocomplete_sub_activity')->insert(['name' => $value['name']]);
                if (sizeof(DB::table('autocomplete_satuan')->where('name', $value['satuan'])->get()) == 0)
                    DB::table('autocomplete_satuan')->insert(['name' => $value['satuan']]);
            }
        });
        return redirect()->route('activity.index')->withStatus(__('Kegiatan berhasil dibuat.'))
        ->with('query',$activity_name)
        ->with('month',$request['activity_start_month'])
        ->with('year',$request['activity_start_year']);
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
            if (auth()->user()->role_id == 1 || $sub_activity->activity->created_by_user_id == auth()->user()->id){
                return view('activity.edit', ['sub_activity' => $sub_activity]);
            }else{
                return abort(403, "Anda tidak diizinkan untuk mengedit kegiatan ini.");
            }
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
        $sub_activity = SubActivity::find($id);
        if ($sub_activity == null)
            return abort(404,"Kegiatan atau Sub-Kegiatan yang akan diedit tidak ditemukan");
        if ($sub_activity->activity->created_by_user_id != auth()->user()->id && auth()->user()->role_id != 1)
            return abort(403, "Anda tidak diizinkan untuk mengedit kegiatan ini.");

        $SubActivityOriginal = SubActivity::find($id);
        $field = $request->all();
        $activity_name = $field['activity_name'];
        $activity_kategori = $field['activity_kategori'];
        $sub_activity = [];
        foreach($field as $key=>$value){
            preg_match("/sub_activity_(name|satuan|volume|kode_butir|angka_kredit|keterangan)/", $key, $re);
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
            $id,
            $SubActivityOriginal,
            $activity_name,
            $activity_kategori,
            $sub_activity,
            $request)
            {
            $data = [
                'name' => $activity_name,
                'kategori' => $activity_kategori,
                'created_by_user_id' => auth()->user()->id
            ];
            $startMonth = config('scale.month_reverse')[$request['activity_start_month']];
            $startYear = (int)$request['activity_start_year'];
            $awal = Carbon::parse("$startYear-$startMonth-01")->startOfMonth();
            $endMonth = config('scale.month_reverse')[$request['activity_end_month']];
            $endYear = (int)$request['activity_end_year'];
            $akhir = Carbon::parse("$startYear-$startMonth-28")->endOfMonth();

            $data['awal'] = $awal;
            if($request['issatubulan'] == false){
                $akhir = Carbon::parse("$endYear-$endMonth-28")->endOfMonth();
            }
            $data['akhir'] = $akhir;
            Activity::where('id','=', $SubActivityOriginal->activity_id)->update($data);
            $SubActivityOriginal->update([
                'activity_id' => $SubActivityOriginal->activity_id,
                'name' => $sub_activity['name'],
                'satuan' => $sub_activity['satuan'],
                'volume' => $sub_activity['volume'],
                'kode_butir' => !empty($sub_activity['kode_butir']) ? $sub_activity['kode_butir'] : null,
                'angka_kredit' => !empty($sub_activity['angka_kredit']) ? $sub_activity['angka_kredit'] : null,
                'keterangan' => !empty($sub_activity['keterangan']) ? $sub_activity['keterangan'] : null,

                'pendidikan' => config('scale.pendidikan')[((int)$sub_activity['qualifikasi']['pendidikan']-1)],
                'ti' => config('scale.likert')[((int)$sub_activity['qualifikasi']['ti']-1)],
                'menulis' =>config('scale.likert')[((int)$sub_activity['qualifikasi']['menulis']-1)],
                'administrasi' =>config('scale.likert')[((int)$sub_activity['qualifikasi']['administrasi']-1)],
                'pengalaman_survei' => config('scale.likert')[((int)$sub_activity['qualifikasi']['pengalaman'] -1)],
            ]);
            Assignment::where('sub_activity_id', '=', $id)->update([
                'petugas' => '{}',
                'realisasi' => '{}',
                'keterangan' => '{}',
                'tingkat_kualitas' => '{}',
                'init_assign' => true
            ]);
        });
        return redirect()->route('activity.index')->withStatus(__('Kegiatan berhasil diubah.'))
        ->with('query',$sub_activity['name'])
        ->with('month',$request['activity_start_month'])
        ->with('year',$request['activity_start_year']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub_activity = SubActivity::find($id);
        if ($sub_activity != null){
            if ($sub_activity->activity->created_by_user_id == auth()->user()->id){
                DB::transaction(function() use($sub_activity) {
                    $parentActivityId = $sub_activity->activity_id;
                    $statusDelete = $sub_activity->delete();
                    if ($statusDelete){
                        if (sizeof(DB::table('sub_activity')->where('activity_id',$parentActivityId)->get()) == 0 )
                            Activity::find($parentActivityId)->delete();
                        return response()->json(['status'=>'sukses', 'message'=>'Berhasil menghapus kegiatan'], 202);
                    }else{
                        return response()->json(['status'=>'gagal', 'message'=>'Gagal meghapus kegiatan']);
                    }
                });
            }else{
                return response()->json(['status'=>'gagal', 'message'=>'Anda tidak diizinkan untuk menghapus kegiatan ini.'], 403);
            }
        }else{
            return response()->json(['status'=>'gagal', 'message'=>'ID tidak ditemukan'], 404);
        }
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
