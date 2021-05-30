<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectorApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('director_approvals', function (Blueprint $table) {
            $table->id();
 
            $table->foreignId('user_id')->nullable()
                    ->references('id')->on('users')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->boolean('approval');
            $table->text('comment')->default('');
            $table->datetime('approvalDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('director_approvals');
    }
}
