<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeleksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seleksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->year('tahun');
            $table->date('daftar_mulai');
            $table->date('daftar_selesai');
            $table->date('verifikasi_mulai');
            $table->date('verifikasi_selesai');
            $table->date('wawancara_mulai');
            $table->date('wawancara_selesai');
            $table->text('keterangan')->nullable();
            $table->boolean('is_publish')->default(0);
            $table->foreignId('jenis_id');
            $table->foreign('jenis_id')->references('id')->on('jenis')->restrictOnDelete();
            $table->foreignId('user_id');
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
        Schema::dropIfExists('seleksis');
    }
}
