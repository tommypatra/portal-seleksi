<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopikInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topik_interviews', function (Blueprint $table) {
            $table->id();
            $table->text('keterangan')->nullable();
            $table->smallInteger('bobot')->default(0);
            $table->foreignId('bank_soal_id');
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->restrictOnDelete();
            $table->foreignId('seleksi_id');
            $table->foreign('seleksi_id')->references('id')->on('seleksis')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['bank_soal_id', 'seleksi_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topik_interviews');
    }
}
