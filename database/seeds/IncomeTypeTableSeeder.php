<?php

use Illuminate\Database\Seeder;
use App\IncomeType;
use Illuminate\Support\Facades\DB;

class IncomeTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

		DB::table('income_types')->truncate();

		App\IncomeType::create([
			'name' => 'Employment Salary',
			'recurring_income' => true,
			'monthly_amount' => 4000
		]);

		App\IncomeType::create([
			'name' => 'Investment Returns'
		]);

		App\IncomeType::create([
			'name' => 'Scholarship/Bursary'
		]);

    }
}
