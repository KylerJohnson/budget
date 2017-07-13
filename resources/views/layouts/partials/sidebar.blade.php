<?php
	$request_parameters = Request::segments();
?>

<div class="col-sm-3 col-md-2 sidebar">
	<ul class="nav nav-sidebar">
		<li {!! $request_parameters[0] == 'expenses' ? 'class="active"':''!!} >
			<a href="/expenses">
				Overview
				@if($request_parameters[0] == 'expenses')
					<span class="sr-only">(current)</span>
				@endif
			</a>
		</li>
		<li {!! $request_parameters[0]== 'analytics' ? 'class="active"':'' !!}>
			<a href="/analytics">
				Analytics
				@if($request_parameters[0] == 'expenses')
					<span class="sr-only">(current)</span>
				@endif
			</a>
		</li>
		<li {!! $request_parameters[0]== 'expense_management' ? 'class="active"':'' !!}>
			<a href="/expense_management">
				Expense Management
				@if($request_parameters[0] == 'expenses')
					<span class="sr-only">(current)</span>
				@endif
			</a>
		</li>
	</ul>
</div>

