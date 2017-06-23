@extends('layouts.master')

@section('scripts')

<script src="/js/chart_functions.js"></script>

<script>

// Initialize variables from server

// Monthly variables
var current_month_expense_totals = {
	@foreach($expense_types as $expense_type)
		"{{ $expense_type->name }}": 0,
	@endforeach
};

@foreach($current_month_expenses as $current_month_expense)
	current_month_expense_totals["{{ $current_month_expense->expense_type->name }}"] += {{ $current_month_expense->amount }};
@endforeach

// Historical Variables
var historical_expense_totals = {
	@foreach($expense_types as $expense_type)
		"{{ $expense_type->name }}": [],
	@endforeach
};

// Note: The following is intended to loop through the expenses and
// assign the totals for each expense type to a month/year.  Towards
// being able to loop through by month, I had a look in both PHP and
// JavaScript and I couldn't find any good "next-month" methods
// either in JavaScript or PHP.  As such, I'm making my own.  The
// main issue is that the next month methods that do exist are
// primitive.  As in, given January 31, the next month would be 
// February 31.  This impossibility would be resolved to March 3, or
// March 2, depending on leap years.  For simplicity, and control, I
// am choosing to create a double loop over month's and years and
// parse the data accordingly.  A good TODO would be to update this
// with a better next month style loop if such a method is found.

// Get the oldest and newest dates where expenses occurred.
var expense_start_month = {{ (new DateTime($expenses->min('date')))->format('m') }};
var expense_start_year = {{ (new DateTime($expenses->min('date')))->format('Y') }};

var expense_end_month = {{ (new DateTime($expenses->max('date')))->format('m') }};
var expense_end_year = {{ (new DateTime($expenses->max('date')))->format('Y') }};

var month_loop = expense_start_month;
var year_loop = expense_start_year;

// Loop through month
while(month_loop <= $expense_end_month && year_loop <= $expense_end_year){

	for(var expense_type_loop in historical_expense_totals){
		historical_expense_totals[expense_type_loop].push(
			$expenses->where('month', month_loop)
			->where('year', year_loop)
		)
	}
	//foreach historical expense total
		// push totals for that expense/year and month to that field.
	
	// iterate loop variables
	month_loop ++;
	if(month_loop % 12 == 1){
		year_loop++;
	}
}


// Let's run our functions

// Current Month Spending Chart
plotChart($("#currentMonthSpendingChart"), "pie", current_month_expense_totals, "");

// Historical Spending Chart
plotLineChartTEMP($("#historicalSpendingChart"));

</script>

@endsection

@section('content')

<div class="row">
	<div class="col-md-4">
		<b>Spending for {{ $date->format('F Y') }}</b>

		<canvas id="currentMonthSpendingChart" width="400" height="400"></canvas>
	</div>
	<div class="col-md-4 col-md-offset-2">
		<b>Historical Spending by Month</b>

		<canvas id="historicalSpendingChart" width="600" height="400"></canvas>
	</div>
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Expenses for {{ $date->format('F Y') }}</div>
			<div class="panel-body">
				<p>
					The list of our expenses is included in the table below.  There should be some more features to talk about soon!
				</p>
			</div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Expense Type</th>
						<th>Amount</th>
						<th>Description</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					@if (count($current_month_expenses)>0)
						@foreach ($current_month_expenses as $expense)
							<tr>
								<td>{{ $expense->expense_type->name }}</td>
								<td>{{ $expense->amount }}</td>
								<td>{{ $expense->description }}</td>
								<td>{{ $expense->date }}</td>
							</tr>
						@endforeach
					@else
						We don't have any expenses to display!  (Should add an "Add some expenses" button)
					@endif
				</tbody>
			</table>
			<a href="create"><button class="btn btn-primary">Add an expense</button></a>
			<button>Click </button>
		</div>
	</div><!-- div.col-md-3 -->
</div> <!-- div.row -->
@endsection
