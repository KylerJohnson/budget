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

var expense_totals = {!! json_encode($expense_totals) !!};

// Let's run our functions

// Current Month Spending Chart
plotChart($("#currentMonthSpendingChart"), "pie", current_month_expense_totals, "");

// Historical Spending Chart
plotLineChart($("#historicalSpendingChart"), expense_totals);

</script>

@endsection

@section('content')

<div class="row">
	<div class="col-md-4">
		<b>Spending for {{ $date->format('F Y') }}</b>

		<canvas id="currentMonthSpendingChart" width="400" height="400"></canvas>
	</div>
	<div class="col-md-6">
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
			<a href="/expenses/create/{{ $date->format('m') }}/{{ $date->format('Y') }}"><button class="btn btn-primary">Add an expense</button></a>
			<button>Click </button>
		</div>
	</div><!-- div.col-md-3 -->
</div> <!-- div.row -->
@endsection
