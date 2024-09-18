<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewerSeleksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviewer_seleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seleksi_id');
            $table->foreign('seleksi_id')->references('id')->on('seleksis')->restrictOnDelete();
            $table->foreignId('sub_institusi_id');
            $table->foreign('sub_institusi_id')->references('id')->on('sub_institusis')->restrictOnDelete();
            $table->unique(['seleksi_id', 'sub_institusi_id']);
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
        Schema::dropIfExists('interviewer_seleksis');
    }
}
