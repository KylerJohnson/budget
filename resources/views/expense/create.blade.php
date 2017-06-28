@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-sm-12">
		<h1>Add a new expense</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
		
		<form action="/expenses" method="POST">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="expense_type">Expense Type</label>
				<select id="expense_type" name="expense_type" class="form-control">
					<option value="">Select One</option>
					@foreach($expense_types as $expense_type)
						<option 
							value="{{ $expense_type->id }}"
							{{ old('expense_type') == $expense_type->id ? "SELECTED":"" }}
						>
							{{ $expense_type->name }}
						</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}">
			</div>
			<div class="form-group">
				<label for="amount">Amount</label>
				<div class="input-group">
					<span class="input-group-addon">$</span>
					<input type="text" id="amount" name="amount" class="form-control" value="{{ old('amount') }}">
				</div>
			</div>
			<div class="form-group">
				<label for="date">Date</label>
				@if(old('date'))
					<input type="date" id="date" name="date" class="form-control" value="{{ old('date') }}">
				@else
					<input type="date" id="date" name="date" class="form-control" value="{{ $date->format('Y').'-'.$date->format('m').'-01'}}">
				@endif
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div> <!-- div.col-sm-12 -->
</div> <!-- div.row -->
@endsection
