@extends('layouts.master')

@section('scripts')

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
		<h1>Expense Management</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<h2>Expense Types</h2>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<table class="table table-striped clickable">
				<thead>
					<tr>
						<th>Expense Type</th>
						<th>Monthly Budget</th>
						<th>Monthly Expense</th>
						<th>Monthly Expense Amount</th>
						<th>Recurring End Date</th>
					</tr>
				</thead>
				<tbody>
					@if (count($expense_types)>0)
						@foreach ($expense_types as $expense_type)
							<tr data-expense_type_id="{{ $expense_type->id }}">
								<td>{{ $expense_type->name }}</td>
								<td>{{ $expense_type->monthly_budget }}</td>
								<td>{{ $expense_type->recurring_expense ? "Yes" : "No"}}</td>
								<td>{{ $expense_type->recurring_expense? $expense_type->monthly_amount : "" }}</td>
								<td>
									@if($expense_type->recurring_expense && $expense_type->set_recurring_end_date)
										{{ (new DateTime($expense_type->recurring_end_date))->format('F j, Y') }}
									@elseif($expense_type->recurring_expense)
										Indefinite Expense
									@endif
								</td>
							</tr>
						@endforeach
					@else
						We don't have any expenses to display!  (Should add an "Add some expenses" button)
					@endif
				</tbody>
			</table>
		</div>

		<a href="expense_management/create">
			<button class="btn btn-primary">
				Add an expense type
			</button>
		</a>
	</div>
</div>

@endsection
