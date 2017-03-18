<?php

use Illuminate\Database\Seeder;
use App\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

		DB::table('expenses')->truncate();

		App\Expense::create([
			'expense_type_id' => '1',
			'amount' => '11.00',
			'description' => 'Monday lunch: Tim Hortons',
			'date' => '2017-01-01 0:0:0'
		]);

		App\Expense::create([
			'expense_type_id' => '1',
			'amount' => '10.00',
			'description' => 'Monday lunch: Vietnamese',
			'date' => '2017-01-01 0:0:0'
		]);

		App\Expense::create([
			'expense_type_id' => '3',
			'amount' => '25.50',
			'description' => 'Movie night!',
			'date' => '2017-01-01 0:0:0'
		]);

		App\Expense::create([
			'expense_type_id' => '4',
			'amount' => '40.23',
			'description' => 'Alcohol',
			'date' => '2017-01-02 0:0:0'
		]);

		App\Expense::create([
			'expense_type_id' => '2',
			'amount' => '123.42',
			'description' => 'Shopping for work clothes',
			'date' => '2017-01-01 0:0:0'
		]);

		App\Expense::create([
			'expense_type_id' => '5',
			'amount' => '2103.85',
			'description' => 'Mortgage Payment',
			'date' => '2017-01-15 0:0:0'
		]);
    }
}
