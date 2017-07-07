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
		window.location = "/expenses/"+$(this).attr("data-expenseId")+"/edit";
	});
})

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
@endsection
