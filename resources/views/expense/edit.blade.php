@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-sm-12">
		<h1>Edit expense</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
		
		<form action="/expenses/{{ $expense->id }}" method="POST">
			{{ csrf_field() }}
			<input type="hidden" name="_method" value="PUT">
			<div class="form-group">
				<label for="expense_type">Expense Type</label>
				<select id="expense_type" name="expense_type" class="form-control">
					<option value="">Select One</option>
					@foreach($expense_types as $expense_type)
						<option 
							value="{{ $expense_type->id }}"
							@if(old('expense_type'))
								{{ old('expense_type') == $expense_type->id ? "SELECTED":"" }}
							@else
								{{ $expense->expense_type_id == $expense_type->id ? "SELECTED":"" }}
							@endif
						>
							{{ $expense_type->name }}
						</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<input type="text" id="description" name="description" class="form-control" 
						@if(old('description'))
							value="{{ old('description') }}"
						@else
							value="{{ $expense->description }}"
						@endif
				>
			</div>
			<div class="form-group">
				<label for="amount">Amount</label>
				<div class="input-group">
					<span class="input-group-addon">$</span>
					<input type="text" id="amount" name="amount" class="form-control"
						@if(old('amount'))
							value="{{ old('amount') }}"
						@else
							value="{{ $expense->amount }}"
						@endif
					>
				</div>
			</div>
			<div class="form-group">
				<label for="date">Date</label>
				@if(old('date'))
					<input type="date" id="date" name="date" class="form-control" value="{{ old('date') }}">
				@else
					<input type="date" id="date" name="date" class="form-control" value="{{ $expense->date }}">
				@endif
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div> <!-- div.col-sm-12 -->
</div> <!-- div.row -->
@endsection
