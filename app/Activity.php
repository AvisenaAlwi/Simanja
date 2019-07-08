<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public $table = "activity";

    protected $fillable = ['name', 'kategori', 'volume','tahun', 'awal_bulan', 'akhir_bulan'];
}
