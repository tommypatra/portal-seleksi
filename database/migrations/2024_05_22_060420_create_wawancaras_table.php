<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWawancarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wawancaras', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 6, 2)->nullable();
            $table->foreignId('pendaftar_id');
            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->restrictOnDelete();
            $table->foreignId('role_seleksi_id');
            $table->foreign('role_seleksi_id')->references('id')->on('role_seleksis')->cascadeOnDelete();
            $table->boolean('is_lulus')->nullable();
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
        Schema::dropIfExists('wawancaras');
    }
}
