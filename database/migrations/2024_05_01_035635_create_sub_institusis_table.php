<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubInstitusisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_institusis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis')->nullable();
            $table->string('keterangan')->nullable();
            $table->foreignId('institusi_id');
            $table->foreign('institusi_id')->references('id')->on('institusis')->restrictOnDelete();
            $table->unique(['nama', 'institusi_id']);
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
        Schema::dropIfExists('sub_institusis');
    }
}
