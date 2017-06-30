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

		$current_month_expenses = Expense::yearAndMonth($date)->with('expense_type')->get();

		$current_month_expense_totals = Expense::expenseTotals($current_month_expenses);

		$expense_totals = Expense::allExpenseTotals();

		return view('expense.index', compact('current_month_expenses', 'current_month_expense_totals', 'expense_totals', 'date'));
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
		$request->session()->flash('alert_type', 'alert-success');

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
		$expense_types = ExpenseType::all();
		
		return view('expense.edit', compact('expense', 'expense_types'));
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
		$this->validate($request, [
			'expense_type' => 'required|exists:expense_types,id',
			'description' => 'required|min:5',
			'amount' => 'required|numeric',
			'date' => 'required|date'
		]);

		$expense->expense_type_id = $request->expense_type;
		$expense->amount = $request->amount;
		$expense->description = $request->description;
		$expense->date = $request->date;

		$expense->save();

		$request->session()->flash('status', 'Your expense was updated successfully!');
		$request->session()->flash('alert_type', 'alert-success');

		try{
			$date = new DateTime($request->date);
			return redirect('expenses/'.$date->format('m').'/'.$date->format('Y'));
		}catch(\Exception $e){
			return redirect('expenses');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense, Request $request)
    {
		try{
			$date = new DateTime($expense->date);
		}catch(\Exception $e){
			$date = new DateTime();
		}

		try{
			$expense->delete();

			$request->session()->flash('status', 'Your expense was deleted successfully!');
			$request->session()->flash('alert_type', 'alert-success');

			return redirect('expenses/'.$date->format('m').'/'.$date->format('Y'));
		}catch(\Exception $e){

			$request->session()->flash('status', 'There was an error deleting your request.  Please try again later.');
			$request->session()->flash('alert_type', 'alert-danger');

			return redirect('expenses/'.$date->format('m').'/'.$date->format('Y'));
		}
		
    }
}
