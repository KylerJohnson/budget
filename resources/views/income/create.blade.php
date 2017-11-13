@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-xs-12 page-title">
		<h1>Add income</h1>
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

		<form action="/income" method="POST">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="income-type">Income Type</label>
				<select id="income-type" name="income_type" class="form-control">
					<option value="">Select One</option>
					@foreach($income_types as $income_type)
						<option 
							value="{{ $income_type->id }}"
							{{ old('income_type') == $income_type->id ? "SELECTED":"" }}
						>
							{{ $income_type->name }}
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
