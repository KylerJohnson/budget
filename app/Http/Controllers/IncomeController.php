<?php

namespace App\Http\Controllers;

use App\Income;
use Illuminate\Http\Request;
use DateTime;

class IncomeController extends Controller
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
    public function create($month = null, $year = null)
    {
		// Try and build a date from the request parameters.
		// If it fails, default to the current date.
		try{
			$date = new DateTime($year.'-'.$month);
		}catch (\Exception $e){
			$date = new DateTime;
		}

		return view('income.create', compact('date'));
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
			'description' => 'required|min:5',
			'amount' => 'required|numeric',
			'date' => 'required|date'
		]);
		echo 'store';

		$income = new Income;

		$income->description = $request->description;
		$income->amount = $request->amount;
		$income->date = $request->date;

		$income->save();

		$request->session()->flash('status', 'Your income entry was added successfully!');
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
     * @param  \App\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        //
		echo "Income Edit";
		dd($income);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }
}
