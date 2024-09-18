<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemeriksaSyaratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksa_syarats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id');
            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->restrictOnDelete();
            $table->foreignId('role_seleksi_id');
            $table->foreign('role_seleksi_id')->references('id')->on('role_seleksis')->cascadeOnDelete();
            $table->boolean('is_valid')->nullable();
            $table->text('keterangan')->nullable();

            $table->unique(['pendaftar_id', 'role_seleksi_id']);
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
        Schema::dropIfExists('pemeriksa_syarats');
    }
}
