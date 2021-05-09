<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParamValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('param_values', function (Blueprint $table) {
            $table->id();

            //подразделение, к которому привязан справочник
            $table->foreignId('orgunit_id')->references('id')->on('orgunits')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');

            //подразделение, к которому привязан справочник
            $table->foreignId('orgunit_param_id')->references('id')->on('orgunit_params')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');

            $table->string('dataAsString', 250)->nullable();
            $table->date('dataAsDate')->nullable();
            $table->decimal('dataAsNumber', $precision = 10, $scale = 3)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('param_values');
    }
}
