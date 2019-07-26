<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','nip','role_id','pejabat_penilai_nip',
        'name','email','jabatan','pendidikan','ti','menulis','administrasi',
        'pengalaman_survei','email_verified_at','photo'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class);
    }
    public function multilevel_role()
    {
        $role_id = $this->role->id;
        if ($role_id == 1)
            return [1,2,3];
        else if ($role_id == 2)
            return [2,3];
        else
            return [3];
    }
    function my_own_activity(){
        return $this->hasMany(MyActivity::class, 'created_by_user_id', 'id');
    }
}
