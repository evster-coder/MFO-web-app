<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgunits', function (Blueprint $table) {
            $table->id();

            $table->string('orgUnitCode', 250);

            $table->boolean('hasDictionaries');

            $table->foreignId('orgunit_id')->nullable()->references('id')->on('orgunits')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orgunits');
    }
}
