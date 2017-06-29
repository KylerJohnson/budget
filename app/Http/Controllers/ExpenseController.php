<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month = null, $year = null)
    {
		// Try and build a date from the request parameters.
		// If it fails, default to the current date.
		try{
			$date = new DateTime($year.'-'.$month);
		}catch (\Exception $e){
			$date = new DateTime;
		}

		$expenses = Expense::select(
			'expense_type_id',
			'amount',
			'description',
			'date',
			DB::raw('YEAR(date) AS year, MONTH(date) AS month')
		)->with('expense_type')->get();

		$current_month_expenses = $expenses->where('year', $date->format('Y'))
			->where('month', $date->format('m'));

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

		return view('expense.index', compact('expenses', 'current_month_expenses', 'expense_types', 'date', 'expense_totals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($month = null, $year = null)
    {
		// Try and build a date from the request parameters.
		// If it fails, default to the current date.
		try{
			$date = new DateTime($year.'-'.$month);
		}catch (\Exception $e){
			$date = new DateTime;
		}

		$expense_types = ExpenseType::select('id', 'name')->get();

		return view('expense.create', compact('date', 'expense_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
		$this->validate($request, [
			'expense_type' => 'required|exists:expense_types,id',
			'description' => 'required|min:5',
			'amount' => 'required|numeric',
			'date' => 'required|date'
		]);
		echo 'store';

		$expense = new Expense;

		$expense->expense_type_id = $request->expense_type;
		$expense->amount = $request->amount;
		$expense->description = $request->description;
		$expense->date = $request->date;

		$expense->save();

		$request->session()->flash('status', 'Your expense was added successfully!');

		try{
			$date = new DateTime($request->date);
			return redirect('expenses/'.$date->format('m').'/'.$date->format('Y'));
		}catch(\Exception $e){
			return redirect('expenses');
		}

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
		echo 'show';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
