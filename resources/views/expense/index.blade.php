@extends('layouts.master')

@section('scripts')

<script src="/js/chart_functions.js"></script>

<script>

// Initialize variables from server
var current_month_expense_totals = {!! json_encode($current_month_expense_totals) !!};

var expense_totals = {!! json_encode($expense_totals) !!};

// Current Month Spending Chart
plotChart($("#currentMonthSpendingChart"), "pie", current_month_expense_totals, "");

// Historical Spending Chart
plotLineChart($("#historicalSpendingChart"), expense_totals);

$(function(){
	$("tr").on("click", function(){
		console.log($(this).attr("data-expenseId"));
		window.location = "/expenses/"+$(this).attr("data-expenseId")+"/edit";
	});
})

</script>

@endsection

@section('content')

<div class="row">
	@if(session('status'))
		<div class="alert {{ session('alert_type') }} alert-dismissible text-center" role="alert">
			{{ session('status') }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	@endif
	
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
			<table class="table table-striped clickable">
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
							<tr data-expenseId="{{ $expense->id }}">
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
