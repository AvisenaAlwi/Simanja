<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activity')->onDelete('cascade');
            $table->string('name');
            $table->string('satuan');
            $table->integer('volume')->default(0);
            $table->integer('kuantitas_realisasi')->default(0);
            $table->integer('tingkat_kualitas')->nullable();
            $table->string('kode_butir')->nullable();
            $table->string('angka_kredit')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('petugas')->default('[]');

            $table->string('pendidikan')->default('SMA');
            $table->string('ti')->default('Tinggi');
            $table->string('menulis')->default('Tinggi');
            $table->string('administrasi')->default('Tinggi');
            $table->string('pengalaman_survei')->default('Tinggi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_activity');
    }
}
