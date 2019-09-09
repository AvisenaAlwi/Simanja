<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Assignment;
use App\User;

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
        $currentMonth = Carbon::now()->formatLocalized('%B');
        $month = Input::get('month', 'now');
        $year = Input::get('year', $currentYear);
        if($month == 'now')
            $month = $currentMonth;

        $sub_activity = DB::table('sub_activity')
            ->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'activity.created_by_user_id', '=', 'users.id')
            ->join('assignment', 'assignment.sub_activity_id', '=', 'sub_activity.id')
            ->select([
                'sub_activity.name as sub_activity_name',
                'activity.name as activity_name',
                'users.name as users_name',
                'users.id as users_id',
                'users.photo',
                'sub_activity.*',
                'activity.awal',
                'activity.akhir',
                'assignment.petugas as petugas',
                'assignment.realisasi',
                'assignment.tingkat_kualitas',
                'assignment.keterangan as keterangan_r',
                'nip'
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
        for ($i=0; $i < sizeof($sub_activity); $i++) {
            $sub = $sub_activity[$i];
            $volumeBulanan = json_decode($sub->petugas, true)[auth()->user()->id]["${month}_${year}"];
                if ($volumeBulanan <= 0)
                    unset($sub_activity[$i]);
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
        $start = Carbon::parse('first day of this month');
        $end = Carbon::parse('last day of this month');
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
        $my_activity = DB::table('my_activity')
            //->join('activity', 'sub_activity.activity_id', '=', 'activity.id')
            ->join('users', 'my_activity.created_by_user_id', '=', 'users.id')
            //->join('assignment', 'assignment.sub_activity_id', '=', 'sub_activity.id')
            ->select([
                'my_activity.name as my_activity_name',
                //'activity.name as activity_name',
                'users.id as users_id',
                'my_activity.*',
                'my_activity.id as my_activity_id',
                'my_activity.*',
                'users.name as user_name',
                //'assignment.petugas'
            ])
            ->where('my_activity.id','=',$id)
            ->first();

            $users = DB::table('users')
            ->select([
                'users.*'
            ])
            ->get();
        if ($my_activity != null){
            return view('myactivity.show', ['my_activity' => $my_activity],  ['users' => $users]);
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
        $myActivity = MyActivity::where('id', $id)->first();
        if ($myActivity != null){
            $pejabatPenilai = User::where('id', $myActivity->created_by_user_id)->first()->pejabat_penilai_nip;
            if (Auth::user()->role_id == 1 || $myActivity->created_by_user_id == Auth::id() || $pejabatPenilai == Auth::id())
                return view('myactivity.edit', ['my_activity' => $myActivity]);
            else
                return abort(403, 'Anda tidak diizinkan untuk mengakses halaman ini');
        }else{
            return abort(404, 'Kegiatanku tidak ditemukan');
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
        MyActivity::where('id', '=', $id)->update([
            "name" => $request['name'],
            "kategori" => $request['kategori'],
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
        $myActivity = MyActivity::where('id', $id)->first();
        $name = $myActivity->name;
        if ($myActivity != null){
            if (auth()->user()->id == $myActivity->created_by_user_id){
                if ($myActivity->tingkat_kualitas != 0)
                    return response()->json(['status'=>'gagal', 'message'=>'Gagal meghapus kegiatan karena sudah dinilai']);
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
        $f = Assignment::where('sub_activity_id','=',$id)->first();
        $realisasi = json_decode($f->realisasi, true);
        $keterangan = json_decode($f->keterangan, true);
        $realisasi[$request->user_id][$request->month_year] = $request->realisasi;
        $keterangan[$request->user_id][$request->month_year] = $request->keterangan;
        $f->update([
            'realisasi' => json_encode($realisasi),
            'keterangan' => json_encode($keterangan),
            'update_state' => 0,
            'init_assign' => empty(json_decode($f->petugas, true)) ? true : false,
        ]);
    }
    public function update_my_activity(Request $request, $id){
        $f = MyActivity::where('id','=',$id)->where('created_by_user_id', '=', $request->user_id)->first();
        if($f == null)
            return response()->json(['status'=>'gagal', 'message'=>'Id tidak ditemukan'], 400);
        $f->update([
            'realisasi' => $request->realisasi,
            'keterangan_r' => $request->keterangan
        ]);
        if($f){
            return response()->json(['status'=>'sukses', 'message'=>'Berhasil disimpan'], 202);
        }else{
            return response()->json(['status'=>'gagal', 'message'=>'Data gagal disimpan'], 400);
        }
    }

}
