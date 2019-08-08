<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignment';
    protected $fillable = [
        'id',
        'activity_id',
        'activity_id',
        'sub_activity_id',
        'sub_activity_id',
        'user_id',
        'user_id',
        'petugas',
        'realisasi',
        'tingkat_kualitas',
        'keterangan',
        'update_state',
    ];
    public $guarded = [];
    
    public function activity(){
        return $this->belongsTo(Activity::class);
    }
    public function subActivity(){
        return $this->belongsTo(SubActivity::class, 'sub_activity_id', 'id');
    }
}
