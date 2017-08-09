<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
	public static function targetAnalytics($expense_totals, $expense_types){
		
		/*
		$target_analytics = [
			'expense_type' => [
				'at_most' => bool,
				'dates' => [
					'itterator' => [
						'availble_spending' => decimal,
						'over' => bool,
						'equal' => bool,
						'under' => bool
					]
				]
				'message_success' => string,
				'message_failure' => string
			]
		]
		*/
		$target_analytics = [];

		// For each expense type, start at most recent month,
		// and loop back, say 12 months, to see if there is under budget.
		// If so, start at the most recent under budget, and see how many
		// consecutive months are under budget.  Of course do checks of
		// over or under budget target based on at_most flag.
		
		$expense_types_with_monthly_budget = $expense_types->where('monthly_budget', '>', '0');

		foreach($expense_types_with_monthly_budget as $expense_type){
			$expense_name = $expense_type->name;

			// Initialize expense type.
			$target_analytics[$expense_name] = [
				'at_most' => $expense_type->at_most,
				'dates' => []
			];

			// Itterator will be used to count the number of months.
			$itterator = 0;

			while(!is_null($month_total = array_pop($expense_totals[$expense_name]['amount'])) && $itterator < 12){
				$available_spending = $expense_type->monthly_budget - $month_total;
				$target_analytics[$expense_name]['dates'][$itterator]['available_spending'] = $available_spending;

				if($available_spending < 0){
					$target_analytics[$expense_name]['dates'][$itterator]['over'] = true;
					$target_analytics[$expense_name]['dates'][$itterator]['equal'] = false;
					$target_analytics[$expense_name]['dates'][$itterator]['under'] = false;
				}elseif($available_spending == 0){
					$target_analytics[$expense_name]['dates'][$itterator]['over'] = false;
					$target_analytics[$expense_name]['dates'][$itterator]['equal'] = true;
					$target_analytics[$expense_name]['dates'][$itterator]['under'] = false;
				}else{
					$target_analytics[$expense_name]['dates'][$itterator]['over'] = false;
					$target_analytics[$expense_name]['dates'][$itterator]['equal'] = false;
					$target_analytics[$expense_name]['dates'][$itterator]['under'] = true;
				}

				$itterator ++;
			}

			$target_analytics[$expense_name]['message_success'] = self::generateSuccessMessage($target_analytics[$expense_name], $expense_name);
			$target_analytics[$expense_name]['message_failure'] = self::generateFailureMessage($target_analytics[$expense_name], $expense_name);
		}

		return $target_analytics;
	}

	public static function successes($expense_totals, $expense_types){
		// We will build an array of messages.

		//dd($expense_totals, $expense_types);

		$successes = [];

		// For each expense type, start at most recent month,
		// and loop back, say 5 months, to see if there is under budget.
		// If so, start at the most recent under budget, and see how many
		// consecutive months are under budget.  Of course do checks of
		// over or under budget target based on at_most flag.
		
		$expense_types_with_monthly_budget = $expense_types->where('monthly_budget', '>', '0');

		foreach($expense_types_with_monthly_budget as $expense_type){
			// now check through previous months

			// itterator will be used to count the number of months.
			$itterator = 0;

			// status is used to indicate a success has occured at some point over the itteration.
			$status = false;

			// Used to track if the user met the budget target exactly, or exceeded it positively.  That is, for at most,
			// the user spent strictly less, or for at least, the user spent strictly more.
			$equal = false;
			$exceeded = false;

			while(!is_null($month_total = array_pop($expense_totals[$expense_type->name]['amount'])) && $itterator < 12){
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

	static function generateSuccessMessage($target_analytics, $expense_name){

		$target_collection = collect($target_analytics['dates']);

		$months_equal = $target_collection->where('equal', true)->count();

		if($target_analytics['at_most'] === '0'){
			$months_exceeded = $target_collection->where('over', true)->count();
		}else{
			$months_exceeded = $target_collection->where('under', true)->count();
		}

		if($months_equal > 0 || $months_exceeded > 0){
			$message = "";

			$message .= "You have ";
			
			if($months_equal > 0 && $months_exceeded > 0){
				$message .= "met and ";

			}elseif($months_equal > 0){
				$message .= "met ";
			}

			if($target_analytics['at_most'] === "0"){
				if($months_exceeded){
					$message .= "exceeded ";
				}
			}else{
				if($months_exceeded){
					$message .= "not exceeded ";
				}
			}

			$message .= "your budget target for ".$expense_name." for ";
			
			$number_of_months = $months_equal + $months_exceeded;
			
			if($number_of_months == 1){
				$message .= $number_of_months." month.";
			}else{
				$message .= $number_of_months." months.";
			}

			return $message;
		}
	}

	static function generateFailureMessage($target_analytics, $expense_name){

		$target_collection = collect($target_analytics['dates']);

		if($target_analytics['at_most'] === '0'){
			$months_exceeded = $target_collection->where('under', true)->count();
		}else{
			$months_exceeded = $target_collection->where('over', true)->count();
		}

		if($months_exceeded > 0){
			$message = "";

			$message .= "You have ";
			
			if($target_analytics['at_most'] === "0"){
				if($months_exceeded){
					$message .= "not met ";
				}
			}else{
				if($months_exceeded){
					$message .= "exceeded ";
				}
			}

			$message .= "your budget target for ".$expense_name." for ";
			
			$number_of_months = $months_exceeded;
			
			if($number_of_months == 1){
				$message .= $number_of_months." month.";
			}else{
				$message .= $number_of_months." months.";
			}

			return $message;
		}
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
