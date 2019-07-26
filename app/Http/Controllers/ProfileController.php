<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Assignment;

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
        $jumlahTugasYangDiemban = Assignment::selectRaw("COUNT(`id`) as count")
                        ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$userId\"') = true")
                        ->first()->count;
        return view('profile.index', ['jumlahTugasYangDiemban' => $jumlahTugasYangDiemban]);
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
}
