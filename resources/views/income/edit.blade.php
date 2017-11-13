@extends('layouts.master')

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
						<h4 class="modal-title" id="myModalLabel">Delete income</h4>
					</div>
					<div class="modal-body">
						<p>
							Are you sure you want to delete this income?
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<form action="/income/{{ $income->id }}" method="POST" class="inline">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
							<button type="submit" class="btn btn-danger">Delete Income</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 page-title">
		<h1>Edit income</h1>
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

		<form action="/income/{{ $income->id }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<div class="form-group">
				<label for="income_type">Income Type</label>
				<select id="income_type" name="income_type" class="form-control">
					<option value="">Select One</option>
					@foreach($income_types as $income_type)
						<option 
							value="{{ $income_type->id }}"
							@if(old('income_type'))
								{{ old('income_type') == $income_type->id ? "SELECTED":"" }}
							@else
								{{ $income->income_type_id == $income_type->id ? "SELECTED":"" }}
							@endif
						>
							{{ $income_type->name }}
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
						value="{{ $income->description }}"
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
							value="{{ $income->amount }}"
						@endif
					>
				</div>
			</div>
			<div class="form-group">
				<label for="date">Date</label>
				<input type="date" id="date" name="date" class="form-control"
					@if(old('date'))
						value="{{ old('date') }}"
					@else
						value="{{ $income->date }}"
					@endif
				>
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
				Delete income
			</button>
		</form>
	</div> <!-- div.col-sm-12 -->
</div> <!-- div.row -->
@endsection
