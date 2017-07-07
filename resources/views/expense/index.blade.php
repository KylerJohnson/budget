@extends('layouts.master')

@section('scripts')

<script src="/js/chart_functions.js"></script>

<script>

// Initialize variables from server
var current_month_expense_totals = {!! json_encode($current_month_expense_totals) !!};

var expense_totals = {!! json_encode($expense_totals) !!};

// Current Month Spending Chart
var currentMonthSpendingChart = plotChart($("#currentMonthSpendingChart"), "pie", current_month_expense_totals, {title: "Spending for {{ $date->format('F Y') }}"});

// Historical Spending Chart
var historicalSpendingChart = plotLineChart($("#historicalSpendingChart"), expense_totals, {title: "Historical Spending by Month"});

$(function(){
	$("tr").on("click", function(){
		console.log($(this).attr("data-expenseId"));
		window.location = "/expenses/"+$(this).attr("data-expenseId")+"/edit";
	});
})

$(".chart-legend").html(historicalSpendingChart.generateLegend());

</script>

@endsection

@section('content')

<div class="row">
	<div class="col-xs-12">
		@if(session('status'))
			<div class="alert {{ session('alert_type') }} alert-dismissible text-center" role="alert">
				{{ session('status') }}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
	</div>
</div>
	
<div class="row">
	<div class="col-xs-12 page-title">
		<h1>Expenses for {{ $date->format('F Y') }}</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="chart-container" style="position:relative; height:30vh; width:100%">
			<canvas id="currentMonthSpendingChart" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="col-md-8">
		<div class="chart-container" style="position:relative; height:40vh; width:100%">
			<canvas id="historicalSpendingChart" width="600" height="400"></canvas>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 chart-legend">
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
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
		</div>
		<a href="/expenses/create/{{ $date->format('m') }}/{{ $date->format('Y') }}"><button class="btn btn-primary">Add an expense</button></a>
	</div>
</div>
@endsection
