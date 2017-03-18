<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    //

	protected $fillable = ['name'];
	public function expense()
	{
		return $this->hasMany('App\Expense');
	}
}
