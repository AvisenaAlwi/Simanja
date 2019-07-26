<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index', ['users' => $model->paginate(15)]);
    }

    public function show(User $model)
    {
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $photo = $request->file('photo');
        $photo_name = $request->nip.'_'.$request->name.'.'.$photo->getClientOriginalExtension();
        $photo->storeAs('public/foto',$photo_name);
        $model->create($request->merge([
            'email_verified_at' => now(),
            'photo' => "foto/$photo_name",
            'password' => Hash::make($request->get('password')),
            'pendidikan' => config('scale.pendidikan')[$request['pendidikan']-1],
            'ti' => config('scale.likert')[$request['ti']-1],
            'menulis' => config('scale.likert')[$request['menulis']-1],
            'administrasi' => config('scale.likert')[$request['administrasi']-1],
            'pengalaman_survei' => config('scale.likert')[$request['pengalaman_survei']-1]
            ])->all());

        $photo = $request->file('photo');

        return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User  $user)
    {

        $photo = $request->file('photo');
        $photo_name = $request->nip.'_'.$request->name.'.'.$photo->getClientOriginalExtension();
        $photo->storeAs('public/foto',$photo_name);
        $user->update(
            $request->merge([
                'password' => Hash::make($request->get('password')),
                'photo' => "foto/$photo_name",
                'pendidikan' => config('scale.pendidikan')[$request['pendidikan']-1],
                'ti' => config('scale.likert')[$request['ti']-1],
                'menulis' => config('scale.likert')[$request['menulis']-1],
                'administrasi' => config('scale.likert')[$request['administrasi']-1],
                'pengalaman_survei' => config('scale.likert')[$request['pengalaman_survei']-1]
                ])
                ->except([$request->get('password') ? '' : 'password']
        ));

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User  $user)
    {
        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }

    public function autocomplete_jabatan(){
        $from_database = DB::table('autocomplete_jabatan')
                        ->select('name')
                        ->orderBy('name')
                        ->distinct()
                        ->get();
        return response()->json($from_database);
    }
}
