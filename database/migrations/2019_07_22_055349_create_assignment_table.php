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
            $table->foreign('activity_id')->references('id')->on('activity')->onDelete('cascade');
            $table->unsignedInteger('sub_activity_id');
            $table->foreign('sub_activity_id')->references('id')->on('sub_activity')->onDelete('cascade');
            $table->unsignedSmallInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('petugas')->default('{}');
            $table->json('realisasi')->default('{}');
            $table->json('tingkat_kualitas')->default('{}');
            // $table->string('kode_butir')->nullable();
            // $table->string('angka_kredit')->nullable();
            $table->json('keterangan')->default('{}');
            $table->timestamps();
            $table->tinyInteger('update_state')->default(1);
            $table->boolean('init_assign')->default(true);
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
