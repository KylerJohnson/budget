<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Income extends Model
{
	protected $fillable = ['description', 'amount', 'date'];

	protected $table = 'income';

	public function income_type(){
		return $this->belongsTo('App\IncomeType');
	}

	public function scopeYearAndMonth($query, DateTime $date){
		return $query->whereYear('date', '=', $date->format('Y'))
				->whereMonth('date', '=', $date->format('m'));
	}
}
