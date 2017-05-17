@extends('layouts.master')

@section('scripts')

<script src="/js/chart_functions.js"></script>
<script>
// Initialize variables from server
var chart_data_object = {};

@foreach ($expense_types as $expense_type)
	chart_data_object["{{ $expense_type->name }}"] = {{ $expense_type->expense()->sum("amount") }};
@endforeach

// Let's run our functions
plotChart($("#monthlySpendingChart"), "pie", JSON.stringify(chart_data_object), "");

</script>

@endsection

@section('content')

<section>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-1">
			Overview of current spending (graph)
			<canvas id="monthlySpendingChart" width="400" height="400"></canvas>
		</div>
	</div>
</div>
</section>

<section>
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Expenses</div>
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
						@if (count($expenses)>0)
							@foreach ($expenses as $expense)
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
		</div>
	</div>
</div>
</section>

@endsection
