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
            $table->string('FIO', 200);

            $table->foreignId('orgunit_id')->nullable()->references('id')->on('orgunits')
                            ->onUpdate('set null')
                            ->onDelete('set null');

            $table->string('position', 200)->nullable();
            $table->string('reason', 255)->nullable();

            $table->boolean('blocked')->nullable();
            $table->boolean('needChangePassword')->nullable();

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
