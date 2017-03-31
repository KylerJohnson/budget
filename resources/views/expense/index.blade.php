@extends('layouts.master')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			Overview of current spending (graph)
			<canvas id="myChart" width="400" height="400"></canvas>
		</div>
	</div>
</div>
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
<script>
/*
	$('button').on('click', function(){
		alert('Button click.');
	});
	*/
	</script>

@endsection
