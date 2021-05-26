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


            $table->foreignId('orgunit_id')->references('id')->on('orgunits')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');

            $table->foreignId('clientform_id')->references('id')->on('client_forms')
                        ->onDelete('restrict')
                        ->onUpdate('restrict');

            $table->string('loanNumber', 200);

            //заключение и закрытие
            $table->date('loanConclusionDate');
            $table->date('loanClosingDate')->nullable();

            //статус
            $table->boolean('statusOpen')->default(1);
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
