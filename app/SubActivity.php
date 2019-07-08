<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubActivity extends Model
{
    public $table = 'sub_activity';
    protected $fillable = [
        'activity_id', 'name','satuan', 'volume',
        'kuantitas_realisasi', 'tingkat_kualitas', 'kode_butir',
        'angka_kredit', 'keterangan', 'petugas',
        'tahun', 'it', 'pendidikan', 'menulis', 'pengalaman_survei'
    ];
}
