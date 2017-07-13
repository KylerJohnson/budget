<?php

use Illuminate\Database\Seeder;
use App\ExpenseType;
use Illuminate\Support\Facades\DB;

class ExpenseTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

		DB::table('expense_types')->truncate();

		App\ExpenseType::create([
			'name' => 'Food',
			'month_budget' => 300.00
		]);

		App\ExpenseType::create([
			'name' => 'Clothing',
			'month_budget' => 200.00
		]);

		App\ExpenseType::create([
			'name' => 'Entertainment',
			'month_budget' => 250.00
		]);

		App\ExpenseType::create([
			'name' => 'Alcohol',
			'month_budget' => '150.00'
		]);

		App\ExpenseType::create([
			'name' => 'Mortgage',
			'month_amount' => '1000',
			'is_recurring' => true
		]);

		App\ExpenseType::create([
			'name' => 'Car Payment',
			'month_amount' => 200.00,
			'is_recurring' => true,
			'recurring_end_date' => '2020-01-01'
		]);

		App\ExpenseType::create([
			'name' => 'Utilities',
			'month_amount' => '300.00',
			'is_recurring' => true
		]);

		App\ExpenseType::create([
			'name' => 'Investments',
			'month_amount' => 100.00,
			'is_recurring' => true
		]);

		App\ExpenseType::create([
			'name' => 'Taxes',
			'month_amount' => '100.00',
			'is_recurring' => true
		]);

		App\ExpenseType::create([
			'name' => 'Miscellaneous',
			'month_budget' => 200.00
		]);

    }
}
