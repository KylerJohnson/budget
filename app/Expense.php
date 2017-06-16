<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Expense extends Model
{
	
	protected $fillable = ['expense_type_id', 'amount', 'description', 'date'];
    //
	public function expense_type()
	{
		return $this->belongsTo('App\ExpenseType');
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
