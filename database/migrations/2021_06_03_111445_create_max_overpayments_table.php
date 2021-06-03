<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaxOverpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('max_overpayments', function (Blueprint $table) {
            $table->id();
            $table->date('dateFrom')->nullable();
            $table->date('dateTo')->nullable();
            $table->decimal('multiplicity', 6, 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('max_overpayments');
    }
}
