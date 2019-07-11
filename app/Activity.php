<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public $table = "activity";

    // protected $fillable = ['*'];
    protected $guarded = [];

    public function created_by_user(){
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }
}
