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
            $table->foreign('created_by_user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('kategori');
            $table->date('awal');
            $table->date('akhir')->nullable();
            $table->string('satuan');
            $table->smallInteger('volume');
            $table->smallInteger('kode_butir')->nullable();
            $table->smallInteger('angka_kredit')->nullable();
            $table->text('keterangan')->nullable();
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
