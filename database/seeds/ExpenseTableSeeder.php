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

		// general expenses
		factory(App\Expense::class, 800)->create();

    }
}
