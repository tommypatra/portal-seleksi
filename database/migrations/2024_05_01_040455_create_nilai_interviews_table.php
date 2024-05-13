<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_interviews', function (Blueprint $table) {
            $table->id();
            $table->float('nilai');
            $table->foreignId('wawancara_id');
            $table->foreign('wawancara_id')->references('id')->on('wawancaras')->restrictOnDelete();
            $table->foreignId('topik_interview_id');
            $table->foreign('topik_interview_id')->references('id')->on('topik_interviews')->restrictOnDelete();
            $table->unique(['wawancara_id', 'topik_interview_id']);
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
        Schema::dropIfExists('nilai_interviews');
    }
}
