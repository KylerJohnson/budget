<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_types', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->boolean('recurring_income');
			$table->decimal('monthly_amount', 6, 2)->nullable();
			$table->boolean('set_recurring_end_date')->nullable();
			$table->date('recurring_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_types');
    }
}
