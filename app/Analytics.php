<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
	public static function successes($expense_totals, $expense_types){
		// We will build an array of messages.

		$successes = [];

		// For each expense type, start at most recent month,
		// and loop back, say 5 months, to see if there is under budget.
		// If so, start at the most recent under budget, and see how many
		// consecutive months are under budget.  Of course do checks of
		// over or under budget target based on at_most flag.
		
		$expense_types_with_monthly_budget = $expense_types->where('monthly_budget', '>', '0');

		foreach($expense_types_with_monthly_budget as $expense_type){
			// now check through previous months
			$itterator = 0;
			$status = false;

			// Used to track if the user met the budget target exactly, or exceeded it positively.  That is, for at most,
			// the user spent strictly less, or for at least, the user spent strictly more.
			$equal = false;
			$exceeded = false;

			while(!is_null($month_total = array_pop($expense_totals[$expense_type->name]['amount'])) && $itterator < 5){
				$available_spending = $expense_type->monthly_budget - $month_total;
				if($expense_type->at_most === "0"){
					// at least
					if($available_spending < 0){
						$status = true;
						$exceeded = true;
						break;
					}elseif($available_spending == 0){
						$status = true;
						$equal = true;
						break;
					}
				}else{
					// at most
					if($available_spending > 0){
						$status = true;
						$exceeded = true;
						break;
					}elseif($available_spending == 0){
						$status = true;
						$equal = true;
						break;
					}
				}
				$itterator ++;
			}

			if($status){
				// Now that we have succedded somewhere, we will go back as many months as there are successes.

				// Start indicates the most recent offset where success was found.
				$start = $itterator;
				// End indicates the oldest offset where success was found.
				$end = $itterator;
				
				// itterator < 120 puts a limit on 10 years back.  Just a safeguard for infinte loops.
				while(!is_null($month_total = array_pop($expense_totals[$expense_type->name]['amount'])) && $itterator < 120){
					$available_spending = $expense_type->monthly_budget - $month_total;
					//dd($available_spending, $expense_type->name);
					if($expense_type->at_most === "0"){
						// at least
						if($available_spending < 0){
							$status = "exceeded at least";
							$exceeded = true;
						}elseif($available_spending == 0){
							$status = "met at at least";
							$equal = true;
						}else{
							break;
						}
					}else{
						// at most
						if($available_spending > 0){
							$status = "succedded at most";
							$exceeded = true;
						}elseif($available_spending == 0){
							$status = "met at most";
							$equal = true;
						}else{
							break;
						}
					}

					$itterator ++;
					$end = $itterator;
				}

				$message = self::displayMessage($start, $end, $equal, $exceeded, $expense_type->at_most, $expense_type->name);
				
				$successes[$expense_type->name] = [
					'start' => $start,
					'end' => $end,
					'equal' => $equal,
					'exceeded' => $exceeded,
					'message' => $message
				];

			}else{
				// Failed.  Don't add anything to the success array.
			}

		}

		return $successes;

	}

	public static function improvement($expense_totals, $expense_types){
		//code
	}

	static function displayMessage($start, $end, $equal, $exceeded, $at_most, $expense_type_name){
		$message = "";

		$message .= "You have ";
		
		if($equal && $exceeded){
			$message .= "met and ";
		}elseif($equal){
			$message .= "met ";
		}

		if($at_most === "0"){
			if($exceeded){
				$message .= "exceeded ";
			}
		}else{
			if($exceeded){
				$message .= "not exceeded ";
			}
		}

		$message .= "your budget target for ".$expense_type_name." for ";
		
		$number_of_months = $end - $start +1;
		
		if($number_of_months == 1){
			$message .= $number_of_months." month.";
		}else{
			$message .= $number_of_months." consecutive months.";
		}

		return $message;


	}
}
