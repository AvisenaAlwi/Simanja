<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('created_by_user_id');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('kategori');
            $table->date('awal');
            $table->date('akhir')->nullable();
            $table->string('satuan');
            $table->smallInteger('volume');
            $table->string('kode_butir')->nullable();
            $table->string('angka_kredit')->nullable();
            $table->smallInteger('realisasi')->nullable();
            $table->smallInteger('tingkat_kualitas')->default(0);
            $table->text('keterangan_t')->nullable();
            $table->text('keterangan_r')->nullable();
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
        Schema::dropIfExists('my_activity');
    }
}
