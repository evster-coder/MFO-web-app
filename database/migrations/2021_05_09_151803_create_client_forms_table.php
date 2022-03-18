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

            $table->foreignId('org_unit_id')->references('id')->on('org_units')
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

            $table->decimal('interest_rate', 10, 2);
            $table->decimal('loan_cost', 10, 2);
            $table->date('loan_date');
            $table->integer('loan_term');
            $table->decimal('monthly_payment', 10, 2)->nullable();

            $table->boolean('is_bankrupt');
            $table->boolean('has_credits');

            $table->text('cashier_comment')->nullable();

            $table->string('mobile_phone', 100)->nullable();
            $table->string('home_phone', 100)->nullable();
            $table->string('snils', 100)->nullable();
            $table->string('pensioner_id', 50)->nullable();
            $table->string('actual_residence_address', 500);
            $table->string('passport_residence_address', 500);
            $table->integer('number_of_dependents')->nullable();

            $table->foreignId('marital_status_id')->nullable()->references('id')->on('marital_statuses')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->foreignId('seniority_id')->nullable()->references('id')->on('seniorities')
                    ->onUpdate('set null')
                    ->onDelete('set null');

            $table->foreignId('passport_id')->references('id')->on('passports')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->string('work_place_name', 200);
            $table->string('work_place_address', 500)->nullable();
            $table->string('work_place_position', 100)->nullable();
            $table->string('work_place_phone', 100)->nullable();

            $table->decimal('constain_income', 15);
            $table->decimal('additional_income', 15);

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
