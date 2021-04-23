<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('surname', 200);
            $table->string('name', 200);
            $table->string('patronymic', 200)->nullable();
            $table->date('birthDate');

            //подразделение, к которому привязан справочник
            $table->foreignId('orgunit_id')->nullable()->references('id')->on('orgunits')
                            ->onUpdate('set null')
                            ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
