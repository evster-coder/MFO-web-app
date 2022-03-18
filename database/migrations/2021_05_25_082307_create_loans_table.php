<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('org_unit_id')->references('id')->on('org_units')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');

            $table->foreignId('client_form_id')->references('id')->on('client_forms')
                        ->onDelete('restrict')
                        ->onUpdate('restrict');

            $table->string('loan_number', 200);
            $table->date('loan_conclusion_date');
            $table->date('loan_closing_date')->nullable();
            $table->boolean('status_open')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
