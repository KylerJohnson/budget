<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseType;
use App\IncomeType;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class BudgetSettingsController extends Controller
{
    public function index()
    {
		$expense_types = ExpenseType::orderBy('name')->get();
		$income_types = IncomeType::orderBy('name')->get();

		return view('budget_settings.index', compact('expense_types', 'income_types'));
        //
    }
}
