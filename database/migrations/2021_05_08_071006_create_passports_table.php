<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->string('passportSeries', 50);
            $table->string('passportNumber', 100);
            $table->date('passportDateIssue');
            $table->string('passportIssuedBy', 250);
            $table->string('passportDepartamentCode', 50)->nullable();
            $table->string('passportBirthplace', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passports');
    }
}
