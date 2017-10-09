@extends('layouts.master')

@section('scripts')

<script>

$(function(){
	$(".clickable td").on("click", function(){
		var data_type = $(this).parents('table').attr("data-resource-type");
		console.log(data_type);

		switch(data_type){
			case 'expense':
				// TODO: Change the endpoint to /expense_types
				window.location = "/budget_settings/expense_types/"+$(this).parent().attr("data-resource_type_id")+"/edit";
				break;
			case 'income':
				window.location = "/budget_settings/income_types/"+$(this).parent().attr("data-resource_type_id")+"/edit";
				break;
		}
	});
})

</script><t_%9>

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
		<h1>Budget Settings</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<h2>Income Types</h2>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<table class="table table-striped clickable" data-resource-type="income">
				<thead>
					<tr>
						<th>Income Type</th>
						<th>Monthly Income Amount</th>
						<th>Recurring End Date</th>
					</tr>
				</thead>
				<tbody>
					@if (count($income_types)>0)
						@foreach ($income_types as $income_type)
							<tr data-resource_type_id="{{ $income_type->id }}">
								<td>{{ $income_type->name }}</td>
								<td>
									@if($income_type->recurring_income)
										{{ $income_type->monthly_amount }}
									@endif
								</td>
								<td>
									@if($income_type->recurring_income && $income_type->set_recurring_end_date)
										{{ (new DateTime($income_type->recurring_end_date))->format('F j, Y') }}
									@elseif($income_type->recurring_income && $income_type->monthly_amount)
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

		<a href="{{ route('budget_settings.income_types.create') }}">
			<button class="btn btn-primary">
				Add an income type
			</button>
		</a>
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
			<table class="table table-striped clickable" data-resource-type="expense">
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
							<tr data-resource_type_id="{{ $expense_type->id }}">
								<td>{{ $expense_type->name }}</td>
								<td>
									@php
										if($expense_type->monthly_budget && !is_null($expense_type->at_most)){
											echo ($expense_type->at_most == 1 ? 'At most ':'At least ');
										}
									@endphp
									{{ $expense_type->monthly_budget }}
								</td>
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

		<a href="{{ route('budget_settings.expense_types.create') }}">
			<button class="btn btn-primary">
				Add an expense type
			</button>
		</a>
	</div>
</div>
@endsection
