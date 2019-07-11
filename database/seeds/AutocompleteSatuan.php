<?php

use Illuminate\Database\Seeder;

class AutocompleteSatuan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('autocomplete_satuan')->insert(['name' => 'paket']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Kegiatan']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Responden']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Ubin']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Pegawai']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Jam']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Rumahtangga']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Tabel']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Perusahaan']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Kunjungan']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Peta']);
        DB::table('autocomplete_satuan')->insert(['name' => 'OH']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Dokumen']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Publikasi']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Instansi']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Kecamatan']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Laporan']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Kelurahan']);
        DB::table('autocomplete_satuan')->insert(['name' => 'Blok Sensus']);
    }
}
