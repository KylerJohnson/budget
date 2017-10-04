@extends('layouts.master')

@section('scripts')

<script src="/js/form_functions.js"></script>
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
						<form action="/budget_settings/expense_types/{{ $expense_type->id }}" method="POST" class="inline">
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
		
		<form action="/budget_settings/expense_types/{{ $expense_type->id }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<div class="form-group">
				<label for="expense_type">Expense Type</label>
				<input type="text" id="expense_type" name="expense_type" class="form-control"
					@if(old('expense_type'))
						value="{{ old('expense_type') }}"
					@else
						value="{{ $expense_type->name }}"
					@endif
				>

			</div>
			<div class="form-group">
				<label for="monthly_budget">Monthly Budget</label>
				<div class="input-group">
					<div class="input-group-btn">
						<button type="button" id="monthly-budget-display" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							@if(!is_null(old('at_most')))
								{{ old('at_most') == "0" ? "At least":"At most" }}
							@else
								{{ $expense_type->at_most == "0" ? "At least":"At most" }}
							@endif
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li id="monthly-budget-at-most" class="pointer"><a>At most</a></li>
							<li id="monthly-budget-at-least" class="pointer"><a>At least</a></li>
						</ul>
					</div>
					<span class="input-group-addon">$</span>
					<input type="text" id="monthly_budget" name="monthly_budget" class="form-control"
						@if(old('monthly_budget'))
							value="{{ old('monthly_budget') }}"
						@else
							value="{{ $expense_type->monthly_budget }}"
						@endif
					>
					<input type="text" id="at-most-input" name="at_most"
						@if(!is_null(old('at_most')))
							value="{{ old('at_most') === '0'? 0:1 }}"
						@else
							value="{{ $expense_type->at_most === '0'? 0:1 }}"
						@endif
					hidden>
				</div>
			</div>
			<fieldset class="form-group">
				<legend>Is this a recurring expense?</legend>
				<div class="radio">
					<label>
						<input type="radio" name="recurring_expense" data-toggle_target="recurring_expense_hide" value="1"
							@if(!is_null(old('recurring_expense')))
								{{ old('recurring_expense') == '1' ? 'checked':'' }}
							@else
								{{ $expense_type->recurring_expense == '1' ? 'checked':'' }}
							@endif
						>
						Yes
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="recurring_expense" data-toggle_target="recurring_expense_hide" value="0"
							@if(!is_null(old('recurring_expense')))
								{{ old('recurring_expense') === '0' ? 'checked':'' }}
							@else
								{{ $expense_type->recurring_expense === '0' ? 'checked':'' }}
							@endif
						>
						No
					</label>
				</div>
			</fieldset>
			<div class="recurring_expense_hide
				@if(!is_null(old('recurring_expense')))
					{{ old('recurring_expense') == '1' ? '':'display-none' }}
				@else
					{{ $expense_type->recurring_expense == '1' ? '':'display-none' }}
				@endif
			">
				<div class="form-group">
					<label for="monthly-amount">Monthly Amount</label>
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" id="monthly-amount" name="monthly_amount" class="form-control"
							@if(old('monthly_amount'))
								value="{{ old('monthly_amount') }}"
							@else
								value="{{ $expense_type->monthly_amount }}"
							@endif
						>
					</div>
				</div>
				<fieldset class="form-group">
					<legend>Set an end date for the recurring expense?</legend>
					<div class="radio">
						<label>
							<input type="radio" name="set_recurring_end_date" data-toggle_target="set_recurring_end_hide" value="1"
								@if(!is_null(old('set_recurring_end_date')))
									{{ old('set_recurring_end_date') == '1'? 'checked':'' }}
								@else
									{{ $expense_type->set_recurring_end_date == '1' ? 'checked':'' }}
								@endif
							>
							Yes
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="set_recurring_end_date" data-toggle_target="set_recurring_end_hide" value="0"
								@if(!is_null(old('set_recurring_end_date')))
									{{ old('set_recurring_end_date') === '0' ? 'checked':''}}
								@else
									{{ $expense_type->set_recurring_end_date === '0' ? 'checked':'' }}
								@endif
							>
							No
						</label>
					</div>
				</fieldset>
				<div class="set_recurring_end_hide
					@if(!is_null(old('set_recurring_end_date')))
						{{ old('set_recurring_end_date') == '1' ? '':'display-none' }}
					@else
						{{ $expense_type->set_recurring_end_date === '1' ? '' : 'display-none' }}
					@endif
				">
					<div class="form-group">
						<label for="recurring-end-date">Recurring End Date</label>
						<input type="date" id="recurring-end-date" name="recurring_end_date" class="form-control"
							@if(old('recurring_end_date'))
								value="{{ old('recurring_end_date') }}"
							@else
								value="{{ $expense_type->recurring_end_date }}"
							@endif
						>
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
