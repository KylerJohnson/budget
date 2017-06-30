<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class Expense extends Model
{
	
	protected $fillable = ['expense_type_id', 'amount', 'description', 'date'];
    //
	public function expense_type()
	{
		return $this->belongsTo('App\ExpenseType');
	}

	public static function expenseTotals($expenses){
		// note that $expenses is assumed to be a collection of type Expense.

		$expense_types = ExpenseType::get();

		foreach($expense_types as $expense_type){
			$expense_totals[$expense_type->name] =
				$expenses->where('expense_type_id', $expense_type->id)->sum('amount');
		}

		return $expense_totals;
	}

	public static function allExpenseTotals(){
		$expenses = self::addYearAndDate()->with('expense_type')->get();
		$expense_types = ExpenseType::get();

		// We will generate an array with expense types as the key, and values of arrays with the total, and date.

		foreach ($expense_types as $expense_type){
			$expense_totals [$expense_type->name] = [];
		}

		// This could be tweaked to show only up to the currently selected
		// month, but since a user can select a previous month, it makes
		// sense to show data up to the present.
		$oldest_expense_date = new DateTime($expenses->min('date'));
		$newest_expense_date = new DateTime($expenses->max('date'));

		// Loop variables
		$date_loop = $oldest_expense_date;
		$litmus = true;

		while($litmus){

			// Get the expenses for the current month with respect
			// to the loop.
			$current_month_expenses_loop =
				$expenses->where('year', $date_loop->format('Y'))
					->where('month', $date_loop->format('m'));

			foreach ($expense_totals as $expense_type => $expense_type_array){

				$current_month_expense_loop_sum =
					$current_month_expenses_loop->filter(function($value, $key) use ($expense_type){
							return $value->expense_type->name == $expense_type;
						})->sum('amount');

				// Arrays of data associated with each expense type.
				// Each array's entries correspond to data for a
				// given month.
				$expense_totals[$expense_type]['amount'][] = $current_month_expense_loop_sum;
				$expense_totals[$expense_type]['month'][] = $date_loop->format('m');
				$expense_totals[$expense_type]['year'][] = $date_loop->format('Y');
				$expense_totals[$expense_type]['displayDate'][] = $date_loop->format('F Y');

			}

			// Iterate

			// I haven't been able to find any good 'next month' type
			// functions in either JavaScript or PHP.  The issue with
			// most is odd day reconciling.  For example, January 31
			// plus a month would be March 3 or March 2 depending on
			// leap years.  Since I only care about months and years,
			// the following seemed to be the simplest

			$date_loop = new DateTime($date_loop->add(new DateInterval('P1M'))->format('Y-m'));

			if($newest_expense_date->diff($date_loop)->invert == 1){
				$litmus = true;
			}elseif($newest_expense_date->diff($date_loop)->days == 0){
				$litmus = true;
			}else{
				$litmus = false;
			}
		}

		return $expense_totals;

	}

	public function scopeAddYearAndDate($query){
		return $query->select(
			'id',
			'expense_type_id',
			'amount',
			'description',
			'date',
			DB::raw('YEAR(date) AS year, MONTH(date) AS month')
		);
	}

	public function scopeCurrentMonth($query){
		return $query->whereYear('date', '=', date('Y'))
				->whereMonth('date', '=', date('m'));
	}

	public function scopeYearAndMonth($query, DateTime $date){
		return $query->whereYear('date', '=', $date->format('Y'))
				->whereMonth('date', '=', $date->format('m'));
	}
}
