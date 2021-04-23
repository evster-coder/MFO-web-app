<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {
            //внешний ключ на id роли
            $table->foreignId('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            //внешний ключ на id права
            $table->foreignId('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            //составной первичный ключ
            $table->primary(['role_id','permission_id']);

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
        Schema::dropIfExists('role_permission');
    }
}
