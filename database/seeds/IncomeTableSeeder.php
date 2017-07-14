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

		factory(Income::class, 100)->create();

    }
}
