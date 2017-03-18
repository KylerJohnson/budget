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

		App\ExpenseType::create(['name' => 'Food']);
		App\ExpenseType::create(['name' => 'Clothing']);
		App\ExpenseType::create(['name' => 'Entertainment']);
		App\ExpenseType::create(['name' => 'Alcohol']);
		App\ExpenseType::create(['name' => 'Mortgage']);
		App\ExpenseType::create(['name' => 'Car Payment']);
		App\ExpenseType::create(['name' => 'Utilities']);
		App\ExpenseType::create(['name' => 'Investments']);
		App\ExpenseType::create(['name' => 'Taxes']);
		App\ExpenseType::create(['name' => 'Miscellaneous']);
    }
}
