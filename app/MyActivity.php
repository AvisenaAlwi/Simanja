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
        'realisasi',
        'tingkat_kualitas',
        'keterangan_t',
        'keterangan_r',
    ];
    public $gurared = ['id'];

    function owner(){
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }
}
