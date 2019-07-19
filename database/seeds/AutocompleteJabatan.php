<?php

use Illuminate\Database\Seeder;

class AutocompleteJabatan  extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('autocomplete_jabatan')->insert(['name' => 'Kepala']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Kasubbag Tata Usaha']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Kasi Statistik Sosial']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Kasi Statistik Produksi']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Kasi Statistik Distribusi']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Kasi Nerwilis']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Kasi IPDS']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Bendahara Pengeluaran']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Bendahara Penerimaan']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Pengelola Barang Persediaan dan BMN']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Pelaksana Lanjutan']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Pertama']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Muda']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Penyelia/KSK Kedungkandang']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Pelaksana Lanjutan/KSK Sukun']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Penyelia/KSK Klojen']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Penyelia/KSK Blimbing']);
        DB::table('autocomplete_jabatan')->insert(['name' => 'Statistisi Pelaksana/KSK Lowokwaru']);
    }
}

