<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activity');
            $table->unsignedInteger('sub_activity_id');
            $table->foreign('sub_activity_id')->references('id')->on('sub_activity');
            $table->unsignedSmallInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->json('petugas');
            $table->smallInteger('realisasi');
            $table->tinyInteger('tingkat_kualitas')->nullable();
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
        Schema::dropIfExists('assignment');
    }
}
