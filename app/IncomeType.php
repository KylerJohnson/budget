<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model
{
    //
	protected $fillable = ['name'];

	public function income(){
		return $this->hasMany('App\Income');
	}
}
