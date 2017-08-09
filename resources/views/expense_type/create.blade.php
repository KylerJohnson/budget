@extends('layouts.master')

@section('scripts')

<script src="/js/form_functions.js">
</script>
<script>

$(function(){
	$("#monthly-budget-at-most").on("click", function(){
		// change button text
		$("#monthly-budget-display").html("At most <span class='caret'></span>");

		// change hidden field value
		$("#at-most-input").val("1");
	});

	$("#monthly-budget-at-least").on("click", function(){
		// change button text
		$("#monthly-budget-display").html("At least <span class='caret'></span>");

		// change hidden field value
		$("#at-most-input").val("0");
	});
});
</script>

@endsection

@section('content')

<div class="row">
	<div class="col-sm-12 page-title">
		<h1>Add a new expense</h1>
	</div>
	
	<div class="col-xs-12">
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form action="/expense_management" method="POST">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="expense_type">Expense Type</label>
				<input type="text" id="expense_type" name="expense_type" class="form-control" value="{{ old('expense_type') }}">
			</div>
			<div class="form-group">
				<label for="monthly_budget">Monthly Budget</label>
				<div class="input-group">
					<div class="input-group-btn">
						<button type="button" id="monthly-budget-display" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							{{ old('at_most') === "0" ? "At least":"At most" }} <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li id="monthly-budget-at-most" class="pointer"><a>At most</a></li>
							<li id="monthly-budget-at-least" class="pointer"><a>At least</a></li>
						</ul>
					</div>
					<span class="input-group-addon">$</span>
					<input type="text" id="monthly-budget" name="monthly_budget" class="form-control" value="{{ old('monthly_budget') }}">
					<input type="text" id="at-most-input" name="at_most" value="{{ old('at_most') === '0'? 0:1 }}" hidden>
				</div>
			</div>
			<fieldset class="form-group">
				<legend>Is this a recurring expense?</legend>
				<div class="radio">
					<label>
						<input type="radio" name="recurring_expense" data-toggle_target="recurring_expense_hide" value="1" {{ old('recurring_expense') === "1" ? "checked":"" }}>
						Yes
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="recurring_expense" data-toggle_target="recurring_expense_hide" value="0" {{ old('recurring_expense') === "0" ? "checked":"" }}>
						No
					</label>
				</div>
			</fieldset>
			<div class="recurring_expense_hide {{ old('recurring_expense') === "1" ? "" : "display-none" }}">
				<div class="form-group">
					<label for="monthly-amount">Monthly Amount</label>
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" id="monthly-amount" name="monthly_amount" class="form-control" value="{{ old('monthly_amount') }}">
					</div>
				</div>
				<fieldset class="form-group">
					<legend>Set an end date for the recurring expense?</legend>
					<div class="radio">
						<label>
							<input type="radio" name="set_recurring_end_date" data-toggle_target="set_recurring_end_hide" value="1" {{ old('set_recurring_end_date') === "1" ? "checked":"" }}>
							Yes
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="set_recurring_end_date" data-toggle_target="set_recurring_end_hide" value="0" {{ old('set_recurring_end_date') === "0" ? "checked":"" }}>
							No
						</label>
					</div>
				</fieldset>
				<div class="set_recurring_end_hide {{ old('set_recurring_end_date') === "1" ? "" : "display-none" }}">
					<div class="form-group">
						<label for="recurring-end-date">Recurring End Date</label>
						<input type="date" id="recurring-end-date" name="recurring_end_date" class="form-control" value="{{ old('recurring_end_date') }}">
						<span class="help-block">An expense will be added each month starting next month up to and including the month of the date provided.</span>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="start_now" value="true" {{ old('start_now') === 'true' ? "checked" : "" }}>
							Start this month
						</label>
					</div>
				</div>
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div> <!-- div.col-sm-12 -->
</div> <!-- div.row -->
@endsection
