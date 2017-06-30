<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTime;

class ExpenseType extends Model
{

	protected $fillable = ['name'];

	public function expenses()
	{
		return $this->hasMany('App\Expense');
	}

}
