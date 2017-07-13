@extends('layouts.master')

@section('scripts')

<script src="/js/form_functions.js"></script>

@endsection

@section('content')

<div class="row">
	<div class="col-sm-12">
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Delete expense</h4>
					</div>
					<div class="modal-body">
						<p>
							Are you sure you want to delete this expense type?
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<form action="/expense_management/{{ $expense_type->id }}" method="POST" class="inline">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
							<button type="submit" class="btn btn-danger">Delete Expense Type</button>
						</form>
					</div>
					</div>
				</div>
			</div>
		</div>
	<div class="col-sm-12">
		<h1>Edit expense type</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
		
		<form action="/expense_management/{{ $expense_type->id }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<div class="form-group">
				<label for="expense_type">Expense Type</label>
				<input type="text" id="expense_type" name="expense_type" class="form-control" value="{{ $expense_type->name }}">
			</div>
			<div class="form-group">
				<label for="monthly_budget">Monthly Budget</label>
				<div class="input-group">
					<span class="input-group-addon">$</span>
					<input type="text" id="monthly_budget" name="monthly_budget" class="form-control" value="{{ $expense_type->monthly_budget }}">
				</div>
			</div>
			<fieldset class="form-group">
				<legend>Is this a recurring expense?</legend>
				<div class="radio">
					<label>
						<input type="radio" name="recurring_expense" data-toggle_target="recurring_expense_hide" value="1" {{ $expense_type->recurring_expense === "1" ? "checked":"" }}>
						Yes
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="recurring_expense" data-toggle_target="recurring_expense_hide" value="0" {{ $expense_type->recurring_expense === "0" ? "checked":"" }}>
						No
					</label>
				</div>
			</fieldset>
			<div class="recurring_expense_hide {{ $expense_type->recurring_expense === "1" ? "" : "display-none" }}">
				<div class="form-group">
					<label for="monthly-amount">Monthly Amount</label>
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" id="monthly-amount" name="monthly_amount" class="form-control" value="{{ $expense_type->monthly_amount }}">
					</div>
				</div>
				<fieldset class="form-group">
					<legend>Set an end date for the recurring expense?</legend>
					<div class="radio">
						<label>
							<input type="radio" name="set_recurring_end_date" data-toggle_target="set_recurring_end_hide" value="1" {{ $expense_type->set_recurring_end_date === "1" ? "checked":"" }}>
							Yes
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="set_recurring_end_date" data-toggle_target="set_recurring_end_hide" value="0" {{ $expense_type->set_recurring_end_date === "0" ? "checked":"" }}>
							No
						</label>
					</div>
				</fieldset>
				<div class="set_recurring_end_hide {{ $expense_type->set_recurring_end_date === "1" ? "" : "display-none" }}">
					<div class="form-group">
						<label for="recurring-end-date">Recurring End Date</label>
						<input type="date" id="recurring-end-date" name="recurring_end_date" class="form-control" value="{{ $expense_type->recurring_end_date }}">
						<span class="help-block">An expense will be added each month starting next month up to and including the month of the date provided.</span>
					</div>
				</div>
			</div>
						<button type="submit" class="btn btn-primary">Submit</button>
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
				Delete expense
			</button>
		</form>
	</div> <!-- div.col-sm-12 -->
</div> <!-- div.row -->
@endsection
