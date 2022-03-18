<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecurityApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()
                    ->references('id')->on('users')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->boolean('approval');
            $table->text('comment')->nullable();
            $table->datetime('approval_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security_approvals');
    }
}
