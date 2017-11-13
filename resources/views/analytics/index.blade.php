@extends('layouts.master')

@section('scripts')

<script src="/js/chart_functions.js"></script>

<script>

// Initialize variables from server
var current_month_expense_totals = {!! json_encode($current_month_expense_totals) !!};

var budget_totals = {!! json_encode($budget_totals) !!};

// Current Month Spending Chart
var currentMonthSpendingChart = plotChart($("#currentMonthSpendingChart"), "pie", current_month_expense_totals, {title: "Spending for {{ $date->format('F Y') }}"});

// Historical Spending Chart
var historicalSpendingChart = plotLineChart($("#historicalSpendingChart"), budget_totals, {title: "Historical Spending by Month"});

$(".chart-legend").html(historicalSpendingChart.generateLegend());

</script>

@endsection

@section('content')

<div class="row">
	<div class="col-xs-12 page-title">
		<h1>Analytics</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="chart-container" style="position:relative; height:35vh; width:100%">
			<canvas id="currentMonthSpendingChart" width="400" height="400"></canvas>
		</div>
	</div>

	<div class="col-md-9">
		<div class="chart-container" style="position:relative; height:40vh; width:100%">
			<canvas id="historicalSpendingChart" width="600" height="400"></canvas>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 chart-legend">
	</div>
</div>

<div class="row display-none">
	<div class="col-xs-12">
		<h2>Budget overview for {{ $date->format('F Y') }}</h2>
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
</div>

<div class="row">
	<div class="col-md-6">
		<h2>Areas of success</h2>
		<ul>
			@foreach($target_analytics as $expense_type=>$analytics)
				@if($analytics['message_success'])
					<li>{{ $analytics['message_success'] }}</li>
				@endif
			@endforeach
		</ul>
	</div>
	<div class="col-md-6">
		<h2>Areas for improvement</h2>
		<ul>
			@foreach($target_analytics as $expense_type=>$analytics)
				@if($analytics['message_failure'])
					<li>{{ $analytics['message_failure'] }}</li>
				@endif
			@endforeach
		</ul>
	</div>
</div>
@endsection
