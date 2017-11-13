<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class Income extends Model
{
	protected $fillable = ['description', 'amount', 'date'];

	protected $table = 'income';

	public function income_type(){
		return $this->belongsTo('App\IncomeType');
	}

	public static function incomeTotals($income){
		// note that $income is assumed to be a collection of type Expense.

		$income_types = ExpenseType::get();

		foreach($income_types as $income_type){
			$income_totals[$income_type->name] =
				$income->where('income_type_id', $income_type->id)->sum('amount');
		}

		return $income_totals;
	}

	public static function allIncomeTotals(){
		$income = self::addYearAndDate()->with('income_type')->get();
		$income_types = IncomeType::get();

		// We will generate an array with income types as the key, and values of arrays with the total, and date.

		foreach ($income_types as $income_type){
			$income_totals [$income_type->name] = [];
		}

		// This could be tweaked to show only up to the currently selected
		// month, but since a user can select a previous month, it makes
		// sense to show data up to the present.
		$oldest_income_date = new DateTime($income->min('date'));
		$newest_income_date = new DateTime($income->max('date'));

		// Loop variables
		$date_loop = $oldest_income_date;
		$litmus = true;

		while($litmus){

			// Get the income for the current month with respect
			// to the loop.
			$current_month_income_loop =
				$income->where('year', $date_loop->format('Y'))
					->where('month', $date_loop->format('m'));

			foreach ($income_totals as $income_type => $income_type_array){

				$current_month_income_loop_sum =
					$current_month_income_loop->filter(function($value, $key) use ($income_type){
							return $value->income_type->name == $income_type;
						})->sum('amount');

				// Arrays of data associated with each income type.
				// Each array's entries correspond to data for a
				// given month.
				$income_totals[$income_type]['amount'][] = $current_month_income_loop_sum;
				$income_totals[$income_type]['month'][] = $date_loop->format('m');
				$income_totals[$income_type]['year'][] = $date_loop->format('Y');
				$income_totals[$income_type]['displayDate'][] = $date_loop->format('F Y');

			}

			// Iterate

			// I haven't been able to find any good 'next month' type
			// functions in either JavaScript or PHP.  The issue with
			// most is odd day reconciling.  For example, January 31
			// plus a month would be March 3 or March 2 depending on
			// leap years.  Since I only care about months and years,
			// the following seemed to be the simplest

			$date_loop = new DateTime($date_loop->add(new DateInterval('P1M'))->format('Y-m'));

			if($newest_income_date->diff($date_loop)->invert == 1){
				$litmus = true;
			}elseif($newest_income_date->diff($date_loop)->days == 0){
				$litmus = true;
			}else{
				$litmus = false;
			}
		}

		return $income_totals;

	}

	public function scopeAddYearAndDate($query){
		return $query->select(
			'id',
			'income_type_id',
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
