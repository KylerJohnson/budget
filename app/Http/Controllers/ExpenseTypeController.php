<?php

namespace App\Http\Controllers;

use App\ExpenseType;
use App\Expense;
use Illuminate\Http\Request;
use Validator;
use DateTime;
use DateInterval;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$expense_types = ExpenseType::orderBy('name')->get();

		return view('expense_type.index', compact('expense_types'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('expense_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$next_month = new DateTime((new DateTime())->add(new DateInterval('P1M'))->format('Y-m'));

		$validator = Validator::make($request->all(), [
			'expense_type' => ['required','regex:/^(\w+\s)*+\w+$/', 'min:3', 'unique:expense_types,name'],
			'monthly_budget' => 'nullable|numeric',
			'recurring_expense' => 'required|boolean',
			'monthly_amount' => 'required_if:recurring_expense,1',
			'set_recurring_end_date' => 'required_if:recurring_expense,1',
			'recurring_end_date' => 'required_if:set_recurring_end_date,1'
		]);

		$validator->sometimes('at_most', 'required|boolean', function($data){
			return !is_null($data->monthly_budget);
		});

		$validator->sometimes('monthly_amount', 'numeric', function($data){
			return $data->recurring_expense === "1";
		});

		$validator->sometimes('set_recurring_end_date', 'boolean', function($data){
			return $data->recurring_expense === "1";
		});

		$validator->sometimes('recurring_end_date', 'date|after_or_equal:'.$next_month->format('F jS'), function($data){
			return $data->set_recurring_end_date === "1";
		});

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput();
		}

		$expense_type = new ExpenseType;

		$expense_type->name = $request->expense_type;
		$expense_type->monthly_budget = $request->monthly_budget;
		$expense_type->at_most = $request->at_most;
		$expense_type->monthly_amount = $request->monthly_amount;
		$expense_type->recurring_expense = $request->recurring_expense;
		$expense_type->set_recurring_end_date = $request->set_recurring_end_date;
		$expense_type->recurring_end_date = $request->recurring_end_date;


		$expense_type->save();

		if($request->start_now === "true"){
			$expense = new Expense;

			$expense->expense_type_id = $expense_type->id;
			$expense->amount = $expense_type->month_amount;
			$expense->description = "Monthly "+$expense_type->name+" expense.";
			$expense->date = date('Y-m-d H:i:s');

			$expense->save();
		}

		$request->session()->flash('status', 'Your expense type was added successfully!');
		$request->session()->flash('alert_type', 'alert-success');

		return redirect('expense_management');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseType $expenseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseType $expense_management)
    {
		$expense_type = $expense_management;
		return view('expense_type.edit', compact('expense_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseType $expense_management)
    {
		$next_month = new DateTime((new DateTime())->add(new DateInterval('P1M'))->format('Y-m'));

		$expense_type = $expense_management;

		$validator = Validator::make($request->all(), [
			'expense_type' => ['required','regex:/^(\w+\s)*+\w+$/', 'min:3'],
			'monthly_budget' => 'nullable|numeric',
			'recurring_expense' => 'required|boolean',
			'monthly_amount' => 'required_if:recurring_expense,1',
			'set_recurring_end_date' => 'required_if:recurring_expense,1',
			'recurring_end_date' => 'required_if:set_recurring_end_date,1'
		]);

		$validator->sometimes('expense_type', 'unique:expense_types,name', function($data) use ($expense_type){
			return $data->expense_type != $expense_type->name;
		});

		$validator->sometimes('monthly_amount', 'numeric', function($data){
			return $data->recurring_expense === "1";
		});

		$validator->sometimes('set_recurring_end_date', 'boolean', function($data){
			return $data->recurring_expense === "1";
		});

		$validator->sometimes('recurring_end_date', 'date|after_or_equal:'.$next_month->format('F jS'), function($data){
			return $data->set_recurring_end_date === "1";
		});

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput();
		}

		$expense_type->name = $request->expense_type;
		$expense_type->monthly_budget = $request->monthly_budget;
		$expense_type->monthly_amount = $request->monthly_amount;
		$expense_type->recurring_expense = $request->recurring_expense;
		$expense_type->set_recurring_end_date = $request->set_recurring_end_date;
		$expense_type->recurring_end_date = $request->recurring_end_date;


		$expense_type->save();

		$request->session()->flash('status', 'Your expense type was updated successfully!');
		$request->session()->flash('alert_type', 'alert-success');

		return redirect('expense_management');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpenseType  $expenseType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseType $expense_management, Request $request)
    {
		$expense_type = $expense_management;

		// Check that expense type is not being used by an expense
		$expenses = Expense::where('expense_type_id', $expense_type->id)->get();
		
		if(count($expenses)>0){
			$request->session()->flash('status', 'This expense type is being used and cannot be deleted.');
			$request->session()->flash('alert_type', 'alert-danger');

			return redirect('expense_management');
		}

		try{
			$expense_type->delete();

			$request->session()->flash('status', 'Your expense type was deleted successfully!');
			$request->session()->flash('alert_type', 'alert-success');

			return redirect('expense_management');
		}catch(\Exception $e){
			$request->session()->flash('status', 'There was an error processing your request.  Please try again later.');
			$request->session()->flash('alert_type', 'alert-danger');

			return redirect('expense_management');
		}
    }
}
