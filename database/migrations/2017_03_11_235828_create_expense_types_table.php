<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expense_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->decimal('monthly_budget', 6, 2)->nullable();
			$table->boolean('at_most')->nullable();
			$table->boolean('recurring_expense');
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
		Schema::dropIfExists('expense_types');
	}
}
