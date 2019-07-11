<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('kategori')->default('Utama');
            $table->unsignedSmallInteger('created_by_user_id');
            $table->foreign('created_by_user_id')->references('id')->on('users');
            $table->string('bulan_awal');
            $table->string('tahun_awal');
            $table->string('bulan_akhir')->nullable();
            $table->string('tahun_akhir')->nullable();
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
        Schema::dropIfExists('activity');
    }
}
