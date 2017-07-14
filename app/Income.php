<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
	protected $fillable = ['description', 'amount', 'date'];

	protected $table = 'income';
}
