<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'sub_activity';
    public $guarded = [];
    
    public function activity(){
        return $this->belongsTo(Activity::class);
    }
}
