<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseType;
use Illuminate\Http\Request;
use DateTime;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month = null, $year = null)
    {
		try{
			$date = new DateTime($year.'-'.$month);
		}catch (\Exception $e){
			$date = new DateTime;
		}

		$monthly_expenses = Expense::yearAndMonth($date)->with('expense_type')->get();
		$expense_types = ExpenseType::with(['expenses'=> function($query) use ($date){
			$query->yearAndMonth($date);
		}])->get();

		return view('expense.index', compact('monthly_expenses', 'expense_types', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		echo "create";
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
		echo "show";
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
