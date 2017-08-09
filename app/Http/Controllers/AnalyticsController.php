<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\ExpenseType;
use App\Analytics;
use DateTime;

class AnalyticsController extends Controller
{
    //

	public function index(){
		// Try and build a date from the request parameters.
		// If it fails, default to the current date.
		try{
			$date = new DateTime($year.'-'.$month);
		}catch (\Exception $e){
			$date = new DateTime;
		}

		$current_month_expenses = Expense::yearAndMonth($date)->with('expense_type')->get();

		$current_month_expense_totals = Expense::expenseTotals($current_month_expenses);

		$expense_totals = Expense::allExpenseTotals();

		$expense_types = ExpenseType::orderBy('name')->get();

		$successes = Analytics::successes($expense_totals, $expense_types);

		$improvement = Analytics::improvement($expense_totals, $expense_types);
		
		$target_analytics = Analytics::targetAnalytics($expense_totals, $expense_types);
		//dd($target_analytics);

		return view(
			'analytics.index', 
			compact(
				'current_month_expenses',
				'current_month_expense_totals',
				'expense_totals',
				'expense_types',
				'successes',
				'improvement',
				'target_analytics',
				'date'
			)
		);
	}
}
