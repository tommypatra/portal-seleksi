<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identitas', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->default('images/user-avatar.png');
            $table->string('alamat')->nullable();
            $table->string('hp')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->foreignId('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
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
        Schema::dropIfExists('identitas');
    }
}
