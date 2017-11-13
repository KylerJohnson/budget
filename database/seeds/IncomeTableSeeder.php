<?php

use Illuminate\Database\Seeder;
use App\Income;
use Illuminate\Support\Facades\DB;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

		DB::table('income')->truncate();

		// Old style.
		//factory(Income::class, 100)->create();

		// Let's favor creating a year's income.

		// Starts as current date.
		// Used through the following loop to loop through months of the year.
		$date = new DateTime();

		// Used for moving back a month.
		$one_month = new DateInterval('P1M');

		for($i=0; $i<12; $i++){
			App\Income::create([
				'income_type_id' => 1,
				'description' => 'Income from full-time job.',
				'amount' => 4000,
				'date' => $date->format('Y-m-0')
			]);

			$date = $date->sub($one_month);
		}

    }
}
