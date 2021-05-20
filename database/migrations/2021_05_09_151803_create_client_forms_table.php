<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_forms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->references('id')->on('clients')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreignId('orgunit_id')->references('id')->on('orgunits')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('user_id')->nullable()
                    ->references('id')->on('users')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->foreignId('security_approval_id')->nullable()
                    ->references('id')
                    ->on('security_approvals')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->foreignId('director_approval_id')->nullable()
                    ->references('id')
                    ->on('director_approvals')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->decimal('interestRate', 10, 2);
            $table->decimal('loanCost', 10, 2);
            $table->date('loanDate');
            $table->integer('loanTerm');

            $table->boolean('isBankrupt');
            $table->boolean('hasCredits');

            $table->text('cashierComment')->nullable();

            $table->string('mobilePhone', 100)->nullable();
            $table->string('homePhone', 100)->nullable();
            $table->string('snils', 100)->nullable();
            $table->string('pensionerId', 50)->nullable();
            $table->string('actualResidenceAddress', 500);
            $table->integer('numberOfDependents')->nullable();

            $table->foreignId('maritalstatus_id')->nullable()->references('id')->on('marital_statuses')
                    ->onUpdate('set null')
                    ->onDelete('set null');
            $table->foreignId('seniority_id')->nullable()->references('id')->on('seniorities')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->string('passportSeries', 50);
            $table->string('passportNumber', 100);
            $table->string('passportDateIssue');
            $table->string('passportIssuedBy', 250);
            $table->string('passportResidenceAddress', 500);
            $table->string('passportDepartamentCode', 50)->nullable();
            $table->string('passportBirthplace', 500)->nullable();

            $table->string('workPlaceName', 200);
            $table->string('workPlaceAddress', 500)->nullable();
            $table->string('workPlacePosition', 100)->nullable();
            $table->string('workPlacePhone', 100)->nullable();

            $table->decimal('constainIncome', 15, 2);
            $table->decimal('additionalIncome', 15, 2);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_forms');
    }
}
