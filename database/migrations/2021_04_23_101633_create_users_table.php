<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 200);
            $table->string('password', 200);
            $table->string('full_name', 200);

            $table->foreignId('org_unit_id')->nullable()->references('id')->on('org_units')
                            ->onUpdate('set null')
                            ->onDelete('set null');
            $table->string('position', 200)->nullable();
            $table->string('reason', 255)->nullable();
            $table->boolean('blocked')->nullable();
            $table->boolean('need_change_password')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
