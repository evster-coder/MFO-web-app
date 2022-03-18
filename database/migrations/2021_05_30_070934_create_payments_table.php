<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->datetime('payment_date');
            $table->decimal('payment_sum', 10, 2);
            $table->foreignId('loan_id')->references('id')
                    ->on('loans')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreignId('user_id')->references('id')
                    ->on('users')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
            $table->foreignId('org_unit_id')->references('id')
                    ->on('org_units')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
