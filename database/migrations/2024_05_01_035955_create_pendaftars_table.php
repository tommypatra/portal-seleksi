<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->foreignId('peserta_id');
            $table->foreign('peserta_id')->references('id')->on('pesertas')->restrictOnDelete();
            $table->foreignId('seleksi_id');
            $table->foreign('seleksi_id')->references('id')->on('seleksis')->restrictOnDelete();

            $table->boolean('verifikasi_lulus')->nullable();
            $table->text('verifikasi_keterangan')->nullable();
            $table->unique(['peserta_id', 'seleksi_id']);
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
        Schema::dropIfExists('pendaftars');
    }
}
