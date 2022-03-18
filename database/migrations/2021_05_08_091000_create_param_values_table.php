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
            $table->foreignId('org_unit_id')->references('id')->on('org_units')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');

            //подразделение, к которому привязан справочник
            $table->foreignId('org_unit_param_id')->references('id')->on('org_unit_params')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');

            $table->string('data_as_string', 250)->nullable();
            $table->date('data_as_date')->nullable();
            $table->decimal('data_as_number', 10, 3)->nullable();

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
