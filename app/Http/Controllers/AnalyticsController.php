<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\ExpenseType;
use App\Analytics;
use App\Income;
use App\IncomeType;
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
		$current_month_income = Income::yearAndMonth($date)->with('income_type')->get();

		// Current month pie chart data
		$current_month_expense_totals = Expense::expenseTotals($current_month_expenses);
		$current_month_income_totals = Income::incomeTotals($current_month_income);

		// Historical line chart data
		$expense_totals = Expense::allExpenseTotals();
		$income_totals = Income::allIncomeTotals();

		$budget_totals = array_merge($expense_totals, $income_totals);

		$expense_types = ExpenseType::orderBy('name')->get();
		
		$target_analytics = Analytics::targetAnalytics($expense_totals, $expense_types);
		//dd($target_analytics);

		return view(
			'analytics.index',
			compact(
				'current_month_expenses',
				'current_month_expense_totals',
				'budget_totals',
				'expense_types',
				'target_analytics',
				'date'
			)
		);
	}
}
