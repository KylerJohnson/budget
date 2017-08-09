@extends('layouts.master')

@section('scripts')

<script src="/js/chart_functions.js"></script>

<script>

// Initialize variables from server
var current_month_expense_totals = {!! json_encode($current_month_expense_totals) !!};

current_month_expense_totals['Available Funds'] = {{ $current_month_available_funds }};

// Current Month Spending Chart
var currentMonthSpendingChart = plotChart($("#currentMonthSpendingChart"), "pie", current_month_expense_totals, {title: "Spending for {{ $date->format('F Y') }}"});

$(function(){
	$(".clickable td").on("click", function(){
		var table_type = $(this).closest("table").attr("data-table_type");
		console.log('table_type');
		if(table_type == "expense"){
			window.location = "/expenses/"+$(this).parent().attr("data-expense_id")+"/edit";
		}else if(table_type == "income"){
			window.location = "/income/"+$(this).parent().attr("data-income_id")+"/edit";
		}
	});
})

$(".chart-legend").html(currentMonthSpendingChart.generateLegend());

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
		<h1>Overview for {{ $date->format('F Y') }}</h1>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Expense</th>
					<th class="text-right">Spending to date</th>
					<th class="text-right">Monthly Budget</th>
					<th class="text-right">Available Spending</th>
				</tr>
			</thead>
			<tbody>
				@foreach($expense_types as $expense_type)
					@if($expense_type->monthly_budget > 0)
					<tr
						@php $available_spending = $expense_type->monthly_budget-$current_month_expense_totals[$expense_type->name]@endphp
						@if($expense_type->at_most !== "0")
							@if($available_spending < 0)
								class="danger"
							@elseif($available_spending <= .1*$expense_type->monthly_budget)
								class="warning"
							@elseif($available_spending > 0)
								class="success"
							@endif
						@else
							@if($available_spending <= 0)
								class="success"
							@elseif($available_spending <= .2*$expense_type->monthly_budget)
								class="warning"
							@elseif($available_spending > 0)
								class="danger"
							@endif
							
						@endif
					>
						<td>{{ $expense_type->name }}</td>
						<td class="text-right">${{ number_format($current_month_expense_totals[$expense_type->name], '2', '.', '') }}</td>
						<td class="text-right">{{ $expense_type->at_most === "0" ? "At least ":"At most " }}${{ $expense_type->monthly_budget }}</td>
						<td class="text-right">
							${{ number_format(abs($available_spending), '2', '.', '') }}
							{{ ($expense_type->at_most !== "0" && $available_spending < 0) ? " over budget":"" }}
						</td>
					</tr>
					@endif
				@endforeach
			</tbody>
		</table>
	</div>

	<div class="col-md-4">
		<div class="chart-container" style="position:relative; height:35vh; width:100%">
			<canvas id="currentMonthSpendingChart" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="col-md-3 col-lg-12 chart-legend">
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<h2>Income</h2>
		<div class="panel panel-default">
			<table class="table table-striped clickable" data-table_type="income">
				<thead>
					<tr>
						<th>Description</th>
						<th>Amount</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					@if (count($current_month_income)>0)
						@foreach ($current_month_income as $income)
							<tr data-income_id="{{ $income->id }}">
								<td>{{ $income->description }}</td>
								<td>{{ $income->amount }}</td>
								<td>{{ $income->date }}</td>
							</tr>
						@endforeach
					@else
						You don't have any income listed.  Add an income entry by clicking Add income below.
					@endif
				</tbody>
			</table>
		</div>
		<a href="/income/create/{{ $date->format('m') }}/{{ $date->format('Y') }}"><button class="btn btn-primary">Add income</button></a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<h2>Expenses</h2>
		<div class="panel panel-default">
			<table class="table table-striped clickable" data-table_type="expense">
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
							<tr data-expense_id="{{ $expense->id }}">
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
