<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyActivity extends Model
{
    public $table = 'my_activity';
    protected $fillable = [
        'created_by_user_id',
        'name',
        'kategori',
        'awal',
        'akhir',
        'satuan',
        'volume',
        'kode_butir',
        'angka_kredit',
        'keterangan',
    ];
    public $gurared = ['id'];

    function owner(){
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }
}
