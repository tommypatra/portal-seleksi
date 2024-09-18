<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleSeleksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_seleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_user_id');
            $table->foreign('role_user_id')->references('id')->on('role_users')->restrictOnDelete();
            $table->foreignId('seleksi_id');
            $table->foreign('seleksi_id')->references('id')->on('seleksis')->restrictOnDelete();
            $table->unique(['role_user_id', 'seleksi_id']);
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
        Schema::dropIfExists('role_seleksis');
    }
}
