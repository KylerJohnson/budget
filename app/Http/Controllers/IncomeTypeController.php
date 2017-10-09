<?php

namespace App\Http\Controllers;

use App\Income;
use App\IncomeType;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use Validator;

class IncomeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('income_type.create');
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
			'income_type' => ['required','regex:/^(\w+\s)*+\w+$/', 'min:3', 'unique:income_types,name'],
			'recurring_income' => 'required|boolean',
			'monthly_amount' => 'required_if:recurring_income,1',
			'set_recurring_end_date' => 'required_if:recurring_income,1',
			'recurring_end_date' => 'required_if:set_recurring_end_date,1'
		]);

		$validator->sometimes('monthly_amount', 'numeric', function($data){
			return $data->recurring_income === "1";
		});

		$validator->sometimes('set_recurring_end_date', 'boolean', function($data){
			return $data->recurring_income === "1";
		});

		$validator->sometimes('recurring_end_date', 'date|after_or_equal:'.$next_month->format('F jS'), function($data){
			return $data->set_recurring_end_date === "1";
		});

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput();
		}

		$income_type = new IncomeType;

		$income_type->name = $request->income_type;
		$income_type->monthly_amount = $request->monthly_amount;
		$income_type->recurring_income = $request->recurring_income;
		$income_type->set_recurring_end_date = $request->set_recurring_end_date;
		$income_type->recurring_end_date = $request->recurring_end_date;


		$income_type->save();

		if($request->start_now === "true"){
			$income = new Income;

			$income->income_type_id = $income_type->id;
			$income->amount = $income_type->month_amount;
			$income->description = "Monthly "+$income_type->name+" income.";
			$income->date = date('Y-m-d H:i:s');

			$income->save();
		}

		$request->session()->flash('status', 'Your income type was added successfully!');
		$request->session()->flash('alert_type', 'alert-success');

		return redirect('budget_settings');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\IncomeType  $incomeType
     * @return \Illuminate\Http\Response
     */
    public function show(IncomeType $incomeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IncomeType  $incomeType
     * @return \Illuminate\Http\Response
     */
    public function edit(IncomeType $income_type)
    {
		return view('income_type.edit', compact('income_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IncomeType  $incomeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IncomeType $income_type)
    {
		$next_month = new DateTime((new DateTime())->add(new DateInterval('P1M'))->format('Y-m'));

		$validator = Validator::make($request->all(), [
			'income_type' => ['required','regex:/^(\w+\s)*+\w+$/', 'min:3'],
			'recurring_income' => 'required|boolean',
			'monthly_amount' => 'required_if:recurring_income,1',
			'set_recurring_end_date' => 'required_if:recurring_income,1',
			'recurring_end_date' => 'required_if:set_recurring_end_date,1'
		]);

		$validator->sometimes('income_type', 'unique:income_types,name', function($data) use ($income_type){
			return $data->income_type != $income_type->name;
		});

		$validator->sometimes('at_most', 'required|boolean', function($data){
			return !is_null($data->monthly_budget);
		});

		$validator->sometimes('monthly_amount', 'numeric', function($data){
			return $data->recurring_income === "1";
		});

		$validator->sometimes('set_recurring_end_date', 'boolean', function($data){
			return $data->recurring_income === "1";
		});

		$validator->sometimes('recurring_end_date', 'date|after_or_equal:'.$next_month->format('F jS'), function($data){
			return $data->set_recurring_end_date === "1";
		});

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput();
		}

		$income_type->name = $request->income_type;
		$income_type->recurring_income = $request->recurring_income;
		$income_type->monthly_amount = $request->monthly_amount;
		$income_type->set_recurring_end_date = $request->set_recurring_end_date;
		$income_type->recurring_end_date = $request->recurring_end_date;


		$income_type->save();

		$request->session()->flash('status', 'Your income type was updated successfully!');
		$request->session()->flash('alert_type', 'alert-success');

		return redirect('budget_settings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IncomeType  $incomeType
     * @return \Illuminate\Http\Response
     */
    public function destroy(IncomeType $income_type, Request $request)
    {

		// Check that income type is not being used by an income
		$incomes = Income::where('income_type_id', $income_type->id)->get();
		
		if(count($incomes)>0){
			$request->session()->flash('status', 'This income type is being used and cannot be deleted.');
			$request->session()->flash('alert_type', 'alert-danger');

			return redirect('budget_settings');
		}

		try{
			$income_type->delete();

			$request->session()->flash('status', 'Your income type was deleted successfully!');
			$request->session()->flash('alert_type', 'alert-success');

			return redirect('budget_settings');
		}catch(\Exception $e){
			$request->session()->flash('status', 'There was an error processing your request.  Please try again later.');
			$request->session()->flash('alert_type', 'alert-danger');

			return redirect('budget_settings');
		}
    }

}
