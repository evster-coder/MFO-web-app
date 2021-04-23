<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role', function (Blueprint $table) {
            
            //внешний ключ на id пользователя
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');

            //внешний ключ на id роли
            $table->foreignId('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            //составной первичный ключ
            $table->primary(['user_id', 'role_id']);

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
        Schema::dropIfExists('user_role');
    }
}
