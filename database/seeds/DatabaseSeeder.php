<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		$this->call(ExpenseTableSeeder::class);
		$this->call(ExpenseTypeTableSeeder::class);
		$this->call(IncomeTableSeeder::class);
		$this->call(IncomeTypeTableSeeder::class);
    }
}
