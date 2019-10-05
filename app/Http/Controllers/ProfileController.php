<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Assignment;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = auth()->user()->id;
        // $jumlahTugasYangDiemban = Assignment::selectRaw("COUNT(`id`) as count, awal,")
        //                 ->join('activity', 'assignment.activity_id', '=', 'activity.id')
        //                 ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
        //                 ->whereDate('awal', '<=', now() )
        //                 ->whereDate('akhir', '>=', now() )
        //                 ->first()->count;
        $jumlahTugasYangDiemban = DB::table('assignment')
            ->join('activity', 'assignment.activity_id', '=', 'activity.id')
            ->select(['awal', 'akhir'])
            ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
            ->whereDate('awal', '<=', now())
            ->whereDate('akhir', '>=', now())
            ->get()->count();
        return view('profile.index', ['jumlahTugasYangDiembanBulanIni' => $jumlahTugasYangDiemban]);
    }

    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        auth()->user()->update($request->all());

        return back()->withStatus(__('Data diri berhasil diubah.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return redirect('profile')->withPasswordStatus(__('Password berhasil diganti.'));
    }

    public function profile($nip)
    {
        $nip = preg_replace('/\s+/', '', $nip);
        if ($nip == preg_replace('/\s+/', '', auth()->user()->nip))
            return redirect()->route('profile.index');
        $user = User::whereRaw("REPLACE(`nip`, ' ', '')='$nip'")->first();
        if ($user ?? ($user = User::whereRaw("REPLACE(`name`, ' ', '')='$nip'")->first())) {
            $now = Carbon::now()->timezone('Asia/Jakarta')->formatLocalized('%B_%Y');
            $assignments = Assignment::whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$user->id\"') = true")
                ->join('activity', 'assignment.activity_id', '=', 'activity.id')
                ->whereDate('awal', '<=', now())
                ->whereDate('akhir', '>=', now())
                ->select(['*', 'assignment.sub_activity_id as sub_activity_id'])
                ->get();
            $assignment = DB::table('assignment')
                ->join('activity', 'assignment.activity_id', '=', 'activity.id')
                ->select(['awal', 'akhir'])
                ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$user->id\"') = true")
                ->whereRaw("petugas LIKE '%$now%'")
                ->whereDate('awal', '<=', now())
                ->whereDate('akhir', '>=', now())
                ->get();
            $count = $assignment->count();
            return view('profile.profile_user', ['user' => $user, 'assignments' => $assignments, 'jumlahTugasYangDiembanBulanIni' => $count]);
        } else {
            return abort(404);
        }
    }
}
