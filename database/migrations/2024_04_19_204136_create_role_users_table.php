<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_users', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_aktif')->default(0);
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete();
            $table->unique(['user_id', 'role_id']);
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
        Schema::dropIfExists('role_users');
    }
}
