<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nip')->unique();
            $table->unsignedTinyInteger('role_id')->nullable()->default(3);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->string('pejabat_penilai_nip')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('jabatan')->default('Staf');
            
            $table->string('pendidikan')->default('SMA');
            $table->string('ti')->default('Tinggi');
            $table->string('menulis')->default('Tinggi');
            $table->string('administrasi')->default('Tinggi');
            $table->string('pengalaman_survei')->default('Tinggi');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
