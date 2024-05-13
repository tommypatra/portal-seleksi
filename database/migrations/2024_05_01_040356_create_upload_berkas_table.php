<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadBerkasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_berkas', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->foreignId('pendaftar_id');
            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->restrictOnDelete();
            $table->foreignId('syarat_id');
            $table->foreign('syarat_id')->references('id')->on('syarats')->restrictOnDelete();
            $table->boolean('verifikasi_valid')->nullable();
            $table->text('verifikasi_keterangan')->nullable();
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
        Schema::dropIfExists('upload_berkas');
    }
}
