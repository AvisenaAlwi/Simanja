<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;

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
        $showing = Input::get('showing', 'Semualevel');
        $searchQuery = Input::get('query', '');
        $user = DB::table('users')
        ->select(['users.*']);
        if (!empty($searchQuery)){
            $user = $user
            ->where('users.name','LIKE',"%$searchQuery%")
            ->where('users.nip','LIKE',"%$searchQuery%", 'OR');
        }
        if ($showing == 'Semualevel') {
            $user = $user
            ->paginate(10);
        }
        elseif ($showing == 'Admin') {
            $user = $user
            ->where('users.role_id','=','1')
            ->paginate(10);
        }
        elseif ($showing == 'Supervisor') {
            $user = $user
            ->where('users.role_id','=','2')
            ->paginate(10);
        }
        else{
            $user = $user
            ->where('users.role_id','=','3')
            ->paginate(10);
        }
        //  dd($user);
        return view('users.index', ['users' => $user , 'showing' => $showing]);
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
        $pejabat_penilai = User::where('role_id', '=', 1)->orWhere('role_id', '=', 2)->get()->all();
        return view('users.create', ['pejabat_penilai' => $pejabat_penilai]);
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
        // $this->validate($request, [
        //     'name' => 'required',
        //     'nip' => 'required|min:18|max:21',
        //     'email' => 'required|email',
        //     'pejabat_penilai_nip' => 'required|exists:users,nip',
        //     'role_id' => 'required|exists:roles,id',
        //     'photo' => 'sometimes|required|image|max:2048',
        // ]);
        $photo = $request->file('photo');
        $temp = [
            'email_verified_at' => now(),
            'password' => Hash::make($request->get('password')),
            'pendidikan' => config('scale.pendidikan')[$request['pendidikan']-1],
            'ti' => config('scale.likert')[$request['ti']-1],
            'menulis' => config('scale.likert')[$request['menulis']-1],
            'administrasi' => config('scale.likert')[$request['administrasi']-1],
            'pengalaman_survei' => config('scale.likert')[$request['pengalaman_survei']-1]
        ];
        $merged = $request->merge($temp)->all();
        if ($photo != null){
            // $photo_name = $request->nip.'_'.$request->name.'.'.$photo->getClientOriginalExtension();
            $photo_name = $request->nip.'_'.$request->name.'.jpg';
            // $photo->storeAs('public/foto',$photo_name);
            Image::make($request->photo_base64)->save(public_path('storage/foto/'.$photo_name, 80));
            $temp['photo'] = "foto/$photo_name";
            $merged['photo'] = "foto/$photo_name";
        }
        $model->create($merged);
        return redirect()->route('user.index')->withStatus('Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $pejabat_penilai = User::where('role_id', '=', 1)->orWhere('role_id', '=', 2)->get()->all();
        return view('users.edit', ['user' => $user, 'pejabat_penilai' => $pejabat_penilai]);
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
        // $request->validate([
        //     'name' => 'required',
        //     'nip' => 'required|min:18|max:21',
        //     'email' => 'required|email',
        //     'pejabat_penilai_nip' => 'required|exists:users,nip',
        //     'role_id' => 'required|exists:roles,id',
        //     'photo' => 'sometimes|required|image|max:2048',
        // ]);
        $photo = $request->file('photo');
        $temp = [
            'pendidikan' => config('scale.pendidikan')[$request['pendidikan']-1],
            'ti' => config('scale.likert')[$request['ti']-1],
            'menulis' => config('scale.likert')[$request['menulis']-1],
            'administrasi' => config('scale.likert')[$request['administrasi']-1],
            'pengalaman_survei' => config('scale.likert')[$request['pengalaman_survei']-1]
        ];
        // dd(!$request->get('password'));
        // dd(empty($request->get('password')));
        $merged = $request->merge($temp)->except([ !empty($request->get('password')) ? '' : 'password' ]);
        if (!empty($request->password))
            $merged['password'] = Hash::make($request->get('password'));
        if ($photo != null){
            $photo_name = $request->nip.'_'.$request->name.'.'.$photo->getClientOriginalExtension();
            // $photo->storeAs('public/foto',$photo_name);
            Image::make($request->photo_base64)->save(public_path('storage/foto/'.$photo_name));
            $temp['photo'] = "foto/$photo_name";
            $merged['photo'] = "foto/$photo_name";
        }
        $user->update($merged);
        return redirect()->route('user.index')->withStatus('Pengguna berhasil diupdate.');
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

        return redirect()->route('user.index')->withStatus(__('Pengguna berhasil dihapus.'));
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
