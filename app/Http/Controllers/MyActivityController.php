<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Assignment;

class MyActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $my_activity = [];
        $currentYear = Carbon::now()->format('Y');
        $month = Input::get('month', 'now');
        $year = Input::get('year', $currentYear);
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
                'assignment.realisasi',
                'assignment.keterangan as keterangan_r'
            ])
            ->selectRaw("CONCAT(sub_activity.name,' ',activity.name) as full_name")
            ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
            ->orderBy('created_at', 'DESC');
        if ($month == 'now' && $year == $currentYear){
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', now() )
                                ->whereDate('akhir', '>=', now() )
                                ->get();
            $my_activity = MyActivity::where('created_by_user_id','=',auth()->user()->id)
                                        ->whereDate('awal', '<=', now() )
                                        ->whereDate('akhir', '>=', now() )->get();
        }else if (in_array($month, config('scale.month')) && $year >= 2019 && $year <= $currentYear){
            $idMonth = (int)config('scale.month_reverse')[$month];
            $date = Carbon::parse("$year-$idMonth-2");
            $sub_activity = $sub_activity
                                ->whereDate('awal', '<=', $date )
                                ->whereDate('akhir', '>=', $date )
                                ->get();
            $my_activity = MyActivity::where('created_by_user_id','=',auth()->user()->id)
                                        ->whereDate('awal', '<=', $date )
                                        ->whereDate('akhir', '>=', $date )->get();
        }else{
            return abort(404, 'bulan atau tahun yang akan dicari tidak valid');
        }
        return view('myactivity.index', ['sub_activity' => $sub_activity, 'my_activity' => $my_activity]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('myactivity.add_myactivity');
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
        $startMonth = config('scale.month_reverse')[$request['start_month']];
        $startYear = $request['start_year'];
        $endMonth = config('scale.month_reverse')[$request['end_month']];
        $endYear = $request['end_year'];
        $start = Carbon::parse("$startYear-$startMonth-1");
        $end = Carbon::parse("$endYear-$endMonth-28")->endOfMonth();
        MyActivity::create([
            "created_by_user_id" => auth()->user()->id,
            "name" => $request['name'],
            "kategori" => $request['kategori'],
            "awal" => $start,
            "akhir" => $end,
            "satuan" => $request['satuan'],
            "volume" => $request['volume'],
            "kode_butir" => $request['kode_butir'],
            "angka_kredit" => $request['angka_kredit'],
            "keterangan_t" => strip_tags($request['keterangan']),
        ]);
        DB::table('autocomplete_activity')->insert([
            'name' => $request['name']
        ]);
        DB::table('autocomplete_satuan')->insert([
            'name' => $request['satuan']
        ]);
        return redirect()->route('myactivity.index')->withStatus(__('Kegiatan berhasil dibuat.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('myactivity.edit', ['my_activity' => MyActivity::find($id)]);
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
        $startMonth = config('scale.month_reverse')[$request['start_month']];
        $startYear = $request['start_year'];
        $endMonth = config('scale.month_reverse')[$request['end_month']];
        $endYear = $request['end_year'];
        $start = Carbon::parse("$startYear-$startMonth-1");
        $end = Carbon::parse("$endYear-$endMonth-28")->endOfMonth();
        MyActivity::where('id', '=', $id)->update([
            "name" => $request['name'],
            "kategori" => $request['kategori'],
            "awal" => $start,
            "akhir" => $end,
            "satuan" => $request['satuan'],
            "volume" => $request['volume'],
            "kode_butir" => $request['kode_butir'],
            "angka_kredit" => $request['angka_kredit'],
            "keterangan_t" => strip_tags($request['keterangan']),
        ]);
        return redirect()->route('myactivity.index')->withStatus(__('Kegiatan berhasil diubah.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $myActivity = MyActivity::find($id);
        $name = $myActivity->name;
        if ($myActivity != null){
            if (auth()->user()->id == $myActivity->created_by_user_id){
                $result = $myActivity->delete();
                if ($result){
                    DB::table('autocomplete_activity')->where('name', '=', $name)->delete();
                    return response()->json(['status'=>'sukses', 'message'=>'Berhasil menghapus kegiatan'], 202);
                }else
                    return response()->json(['status'=>'gagal', 'message'=>'Gagal meghapus kegiatan']);
            }else{
                return response()->json(['status'=>'gagal', 'message'=>'Anda tidak diizinkan untuk menghapus kegiatan ini'], 203);
            }
        }else{
            return response()->json(['status'=>'gagal', 'message'=>'ID tidak ditemukan'], 404);
        }
    }

    public function update_realisasi_keterangan(Request $request, $id){
        // dd($request->all());
        $f = Assignment::where('sub_activity_id','=',$id)->first();
        // dd($f->toJson());
        $realisasi = json_decode($f->realisasi, true);
        $keterangan = json_decode($f->keterangan, true);
        $realisasi[$request->user_id][$request->month_year] = $request->realisasi;
        $keterangan[$request->user_id][$request->month_year] = $request->keterangan;
        $f->update([
            'realisasi' => json_encode($realisasi),
            'keterangan' => json_encode($keterangan)
        ]);
    }

}
