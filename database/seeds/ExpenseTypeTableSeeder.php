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
			'monthly_budget' => 300.00,
			'at_most' => true
		]);

		App\ExpenseType::create([
			'name' => 'Clothing',
			'monthly_budget' => 200.00,
			'at_most' => true
		]);

		App\ExpenseType::create([
			'name' => 'Entertainment',
			'monthly_budget' => 250.00,
			'at_most' => true
		]);

		App\ExpenseType::create([
			'name' => 'Alcohol',
			'monthly_budget' => '150.00',
			'at_most' => true
		]);

		App\ExpenseType::create([
			'name' => 'Mortgage',
			'monthly_amount' => '1000',
			'recurring_expense' => true,
		]);

		App\ExpenseType::create([
			'name' => 'Car Payment',
			'monthly_budget' => 200.00,
			'at_most' => false,
			'monthly_amount' => 200.00,
			'recurring_expense' => true,
			'set_recurring_end_date' => true,
			'recurring_end_date' => '2020-01-01'
		]);

		App\ExpenseType::create([
			'name' => 'Utilities',
			'monthly_amount' => '300.00',
			'recurring_expense' => true
		]);

		App\ExpenseType::create([
			'name' => 'Investments',
			'monthly_budget' => 200.00,
			'at_most' => false,
			'monthly_amount' => 100.00,
			'recurring_expense' => true
		]);

		App\ExpenseType::create([
			'name' => 'Taxes',
			'monthly_amount' => '100.00',
			'recurring_expense' => true
		]);

		App\ExpenseType::create([
			'name' => 'Miscellaneous',
			'monthly_budget' => 200.00,
			'at_most' => true
		]);

    }
}
